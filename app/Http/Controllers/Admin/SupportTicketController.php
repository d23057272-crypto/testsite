<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminRepliedTicketMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')
            ->orderByRaw("FIELD(status, 'open', 'answered', 'closed')")
            ->orderByDesc('last_reply_at')
            ->paginate(20);

        return view('admin.support.index', [
            'title' => 'Support Tickets',
            'tickets' => $tickets
        ]);
    }

    public function downloadAttachment($path)
    {
        $path = str_replace(['..', '\\', "\0"], ['', '/', ''], $path);
        $fullPath = 'ticket-attachments/' . $path;
        if (!Storage::disk('private')->exists($fullPath)) {
            abort(404, 'Attachment not found.');
        }
        return Storage::disk('private')->download($fullPath);
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.support.create', [
            'title' => 'Open Support Ticket',
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:2|max:5000',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = Ticket::create([
            'user_id' => $validated['user_id'],
            'ticket_id' => 'TKT-' . strtoupper(Str::random(8)),
            'subject' => $validated['subject'],
            'priority' => $validated['priority'],
            'status' => 'answered',
            'last_reply_at' => now(),
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'sender_type' => 'admin',
            'is_read' => 0,
        ]);

        // Fetch the actual user instead of the logged-in admin
        $user = \App\Models\User::find($validated['user_id']);
        $notificationService = app(\App\Services\NotificationService::class);

        if ($user) {
            // ✅ Discord alert for new ticket
            try {
                $notificationService->notifyNewTicket($user, $validated['subject'], $ticket->id);
            } catch (\Exception $e) {
                \Log::warning('Discord notification failed: ' . $e->getMessage());
            }

            // Admin DB notification (using users table with is_admin flag)
            try {
                $admin = Auth::user();
                $notificationService->createAdminNotification(
                    $admin->id,
                    'New Support Ticket Created by Admin',
                    "Admin {$admin->name} created ticket #{$ticket->ticket_id} for user {$user->name} ({$user->email}): {$validated['subject']}",
                    'support',
                    $ticket->id,
                    'App\\Models\\Ticket',
                    false
                );
            } catch (\Exception $e) {
                \Log::warning('Admin notification failed: ' . $e->getMessage());
            }
        }

        try {
            Mail::to($ticket->user->email)->send(new AdminRepliedTicketMail($ticket));
        } catch (\Exception $e) {
            \Log::warning('Email notification failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.support.show', $ticket)
            ->with('success', 'Support ticket created successfully.');
    }

    public function openTickets()
    {
        $tickets = Ticket::with('user')->where('status', 'open')->orderByDesc('last_reply_at')->paginate(20);
        return view('admin.support.index', ['title' => 'Open Support Tickets', 'tickets' => $tickets, 'filter' => 'open']);
    }

    public function answeredTickets()
    {
        $tickets = Ticket::with('user')->where('status', 'answered')->orderByDesc('last_reply_at')->paginate(20);
        return view('admin.support.index', ['title' => 'Answered Support Tickets', 'tickets' => $tickets, 'filter' => 'answered']);
    }

    public function closedTickets()
    {
        $tickets = Ticket::with('user')->where('status', 'closed')->orderByDesc('last_reply_at')->paginate(20);
        return view('admin.support.index', ['title' => 'Closed Support Tickets', 'tickets' => $tickets, 'filter' => 'closed']);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('replies.user', 'user');
        TicketReply::where('ticket_id', $ticket->id)
            ->where('sender_type', 'user')
            ->where('is_read', 0)
            ->update(['is_read' => 1, 'read_at' => now()]);

        return view('admin.support.show', [
            'title' => "Ticket: {$ticket->ticket_id}",
            'ticket' => $ticket
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'nullable|string|min:2|max:5000',
            'attachment' => 'nullable|file|max:10240',
        ]);

        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please provide a message or attach a file.'], 422);
            }
            return back()->withErrors(['message' => 'Please provide a message or attach a file.'])->withInput();
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ticket-attachments', 'private');
        }

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'] ?? '',
            'sender_type' => 'admin',
            'attachment' => $attachmentPath,
            'is_read' => 0,
        ]);

        $ticket->update(['status' => 'answered', 'last_reply_at' => now()]);

        try {
            Mail::to($ticket->user->email)->send(new AdminRepliedTicketMail($ticket));
        } catch (\Exception $e) {
            \Log::warning('Email notification failed: ' . $e->getMessage());
        }

        // ✅ DB-only notification for audit trail (no Discord to avoid spam)
        try {
            $notificationService = app(\App\Services\NotificationService::class);
            $admin = Auth::user();
            $notificationService->createAdminNotification(
                $admin->id,
                'Ticket Reply Sent',
                "Admin {$admin->name} replied to ticket #{$ticket->ticket_id}: {$ticket->subject}",
                'info',
                $ticket->id,
                'App\\Models\\Ticket',
                false
            );
        } catch (\Exception $e) {
            \Log::warning('Admin notification failed: ' . $e->getMessage());
        }

        // RETURN JSON FOR AJAX REQUESTS (no page reload)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'reply_id' => $reply->id,
                'message' => $reply->message,
                'attachment' => $reply->attachment,
            ]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close(Ticket $ticket)
    {
        $ticket->update(['status' => 'closed']);

        // Notify admins
        try {
            $notificationService = app(\App\Services\NotificationService::class);
            $admin = Auth::user();
            $notificationService->createAdminNotification(
                $admin->id,
                'Ticket Closed',
                "Admin {$admin->name} closed ticket #{$ticket->ticket_id}: {$ticket->subject}",
                'info',
                $ticket->id,
                'App\\Models\\Ticket'
            );
        } catch (\Exception $e) {
            \Log::warning('Admin notification failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Ticket closed.');
    }

    public function reopen(Ticket $ticket)
    {
        $ticket->update(['status' => 'open']);

        // Notify admins
        try {
            $notificationService = app(\App\Services\NotificationService::class);
            $admin = Auth::user();
            $notificationService->createAdminNotification(
                $admin->id,
                'Ticket Reopened',
                "Admin {$admin->name} reopened ticket #{$ticket->ticket_id}: {$ticket->subject}",
                'alert',
                $ticket->id,
                'App\\Models\\Ticket'
            );
        } catch (\Exception $e) {
            \Log::warning('Admin notification failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Ticket reopened.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticketId = $ticket->ticket_id;
        $ticketSubject = $ticket->subject;

        $ticket->delete();

        // Notify admins
        try {
            $notificationService = app(\App\Services\NotificationService::class);
            $admin = Auth::user();
            $notificationService->createAdminNotification(
                $admin->id,
                'Ticket Deleted',
                "Admin {$admin->name} deleted ticket #{$ticketId}: {$ticketSubject}",
                'danger',
                null,
                null
            );
        } catch (\Exception $e) {
            \Log::warning('Admin notification failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.support.index')->with('success', 'Ticket deleted successfully.');
    }
    
    /**
 * AJAX polling endpoint - returns new replies since last_reply_id
 */
public function poll(Request $request, Ticket $ticket)
{
    $lastReplyId = (int) $request->query('last_reply_id', 0);

    $newReplies = \App\Models\TicketReply::where('ticket_id', $ticket->id)
        ->where('id', '>', $lastReplyId)
        ->with('user')
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function($reply) {
            return [
                'id' => $reply->id,
                'message' => $reply->message,
                'sender_type' => $reply->sender_type,
                'attachment' => $reply->attachment ? basename($reply->attachment) : null,
                'user_name' => $reply->sender_type === 'admin' ? 'Support Team' : ($reply->user?->name ?? 'User'),
                'created_at' => $reply->created_at->format('g:i A'),
            ];
        });

    return response()->json([
        'replies' => $newReplies,
        'ticket_status' => $ticket->status,
    ]);
}

    public function userTickets($userId)
    {
        $user = User::findOrFail($userId);
        $tickets = Ticket::where('user_id', $userId)->orderByDesc('created_at')->paginate(20);
        return view('admin.support.user', ['title' => "Tickets for {$user->name}", 'user' => $user, 'tickets' => $tickets]);
    }
}