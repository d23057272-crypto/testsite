<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserOpenedTicketMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['replies' => function($query) {
                $query->latest()->limit(1);
            }])
            ->forUser(Auth::id())
            ->orderByDesc('last_reply_at')
            ->orderByDesc('created_at')
            ->get()
            ->map(function($ticket) {
                $lastReply = $ticket->replies->first();
                return [
                    'id' => $ticket->ticket_id,
                    'subject' => $ticket->subject,
                    'status' => $ticket->status,
                    'priority' => $ticket->priority,
                    'last_message' => $lastReply ? Str::limit($lastReply->message, 50) : 'No messages yet',
                    'last_sender' => $lastReply ? ($lastReply->isFromAdmin() ? 'Admin' : 'You') : null,
                    'updated_at' => $ticket->last_reply_at ?? $ticket->created_at,
                    'message_count' => $ticket->replies()->count(),
                ];
            });

        return view('user.support.index', [
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
        return view('user.support.create', ['title' => 'New Support Ticket']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
            'priority' => 'in:low,medium,high',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'ticket_id' => $this->generateTicketId(),
            'subject' => $validated['subject'],
            'priority' => $validated['priority'] ?? 'medium',
            'status' => 'open',
            'last_reply_at' => now(),
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'sender_type' => 'user',
        ]);

        $notificationService = app(\App\Services\NotificationService::class);
        $user = auth()->user();

        // ✅ Discord alert for new ticket
        try {
            $notificationService->notifyNewTicket($user, $validated['subject'], $ticket->id);
        } catch (\Exception $e) {
            \Log::warning('Discord notification failed: ' . $e->getMessage());
        }

        // DB notifications for all admin users (using users table with is_admin flag)
        try {
            $admins = \App\Models\User::where('is_admin', true)->orWhere('role', 'admin')->get();
            foreach ($admins as $admin) {
                $notificationService->createAdminNotification(
                    $admin->id,
                    'New Support Ticket',
                    "User {$user->name} ({$user->email}) opened ticket #{$ticket->ticket_id}: {$validated['subject']}",
                    'support',
                    $ticket->id,
                    'App\\Models\\Ticket',
                    false // Discord already sent above
                );
            }
        } catch (\Exception $e) {
            \Log::warning('Admin notification failed: ' . $e->getMessage());
        }

        try {
            Mail::to(config('mail.admin_address'))->send(new UserOpenedTicketMail($ticket, $validated['message']));
        } catch (\Exception $e) {
            \Log::warning('Email notification failed: ' . $e->getMessage());
        }

        return redirect()->route('support.show', $ticket->ticket_id)
            ->with('success', 'Ticket created successfully. We will respond shortly.');
    }

    public function show($ticketId)
    {
        $ticket = Ticket::with('replies.user')
            ->forUser(Auth::id())
            ->where('ticket_id', $ticketId)
            ->firstOrFail();

        TicketReply::where('ticket_id', $ticket->id)
            ->where('sender_type', 'admin')
            ->where('is_read', 0)
            ->update(['is_read' => 1, 'read_at' => now()]);

        return view('user.support.show', [
            'title' => "Ticket: {$ticket->ticket_id}",
            'ticket' => $ticket,
            'replies' => $ticket->replies
        ]);
    }

    // UPDATED: Returns JSON for AJAX, redirects for normal requests
    public function reply(Request $request, $ticketId)
    {
        $ticket = Ticket::forUser(Auth::id())
            ->where('ticket_id', $ticketId)
            ->firstOrFail();

        if ($ticket->isClosed()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This ticket is closed.'], 422);
            }
            return back()->with('error', 'This ticket is closed. Please create a new ticket.');
        }

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
            'sender_type' => 'user',
            'attachment' => $attachmentPath,
            'is_read' => 0,
        ]);

        $ticket->update(['status' => 'open', 'last_reply_at' => now()]);

        // Notify admins that user replied
        try {
            $notificationService = app(\App\Services\NotificationService::class);
            $user = auth()->user();

            // ✅ Discord alert for user reply
            try {
                $discord = app(\App\Services\DiscordService::class);
                $discord->sendAdminAlert(
                    'User Replied to Ticket',
                    "User {$user->name} ({$user->email}) replied to ticket #{$ticket->ticket_id}: {$ticket->subject}\n\n" . Str::limit($validated['message'] ?? '', 200),
                    'support'
                );
            } catch (\Exception $e) {
                \Log::warning('Discord alert failed: ' . $e->getMessage());
            }

            // DB notifications for all admin users
            $admins = \App\Models\User::where('is_admin', true)->orWhere('role', 'admin')->get();
            foreach ($admins as $admin) {
                $notificationService->createAdminNotification(
                    $admin->id,
                    'User Replied to Ticket',
                    "User {$user->name} replied to ticket #{$ticket->ticket_id}: {$ticket->subject}",
                    'support',
                    $ticket->id,
                    'App\\Models\\Ticket',
                    false
                );
            }
        } catch (\Exception $e) {
            \Log::warning('Admin notification failed: ' . $e->getMessage());
        }

        try {
            Mail::to(config('mail.admin_address'))->send(new UserOpenedTicketMail($ticket, $validated['message'] ?? ''));
        } catch (\Exception $e) {
            \Log::warning('Email notification failed: ' . $e->getMessage());
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
    
    /**
 * AJAX polling endpoint - returns new replies since last_reply_id
 */
public function poll(Request $request, $ticketId)
{
    $ticket = Ticket::forUser(Auth::id())
        ->where('ticket_id', $ticketId)
        ->firstOrFail();

    $lastReplyId = (int) $request->query('last_reply_id', 0);

    $newReplies = TicketReply::where('ticket_id', $ticket->id)
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
                'user_name' => $reply->sender_type === 'admin' ? 'Support Team' : ($reply->user?->name ?? 'You'),
                'created_at' => $reply->created_at->format('g:i A'),
            ];
        });

    return response()->json([
        'replies' => $newReplies,
        'ticket_status' => $ticket->status,
    ]);
}

    public function close($ticketId)
    {
        $ticket = Ticket::forUser(Auth::id())->where('ticket_id', $ticketId)->firstOrFail();
        $ticket->update(['status' => 'closed']);

        // Notify admins
        try {
            $notificationService = app(\App\Services\NotificationService::class);
            $user = auth()->user();
            $admins = \App\Models\User::where('is_admin', true)->orWhere('role', 'admin')->get();

            foreach ($admins as $admin) {
                $notificationService->createAdminNotification(
                    $admin->id,
                    'Ticket Closed by User',
                    "User {$user->name} ({$user->email}) closed ticket #{$ticket->ticket_id}: {$ticket->subject}",
                    'info',
                    $ticket->id,
                    'App\\Models\\Ticket',
                    false
                );
            }
        } catch (\Exception $e) {
            \Log::warning('Admin notification failed: ' . $e->getMessage());
        }

        return redirect()->route('support.index')->with('success', 'Ticket closed successfully.');
    }

    private function generateTicketId(): string
    {
        do {
            $id = 'TKT-' . strtoupper(Str::random(8));
        } while (Ticket::where('ticket_id', $id)->exists());
        return $id;
    }
}