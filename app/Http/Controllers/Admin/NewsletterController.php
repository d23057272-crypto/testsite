<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Newsletter;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    /**
     * Display the newsletter dashboard
     */
    public function index()
    {
        $users = User::regular()->get();
        $tinymceApiKey = env('TINYMCE_API_KEY', '');

        return view('admin.newsletter.index', [
            'users' => $users,
            'tinymceApiKey' => $tinymceApiKey
        ]);
    }

    /**
     * Preview newsletter template
     */
    public function preview(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'template' => 'required|in:standard,announcement,promotion'
        ]);

        $previewEmail = auth()->user()->email;

        try {
            Mail::to($previewEmail)->send(new Newsletter(
                auth()->user(),
                $request->subject,
                $request->content,
                $request->template
            ));

            return response()->json([
                'success' => true,
                'message' => 'Preview email sent to ' . $previewEmail
            ]);
        } catch (\Exception $e) {
            Log::error('Newsletter preview failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send preview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send the newsletter
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,selected',
            'selected_users' => 'required_if:recipient_type,selected|array',
            'template' => 'required|in:standard,announcement,promotion'
        ]);

        // Get recipients
        if ($request->recipient_type === 'all') {
            $recipients = User::regular()->get();
        } else {
            $recipients = User::whereIn('id', $request->selected_users)->get();
        }

        if ($recipients->isEmpty()) {
            return back()->with('error', 'No recipients selected.');
        }

        // Send emails
        $sent = 0;
        $failed = 0;
        $errorMessages = [];

        foreach ($recipients as $user) {
            try {
                // Use send() instead of queue() - queue requires worker
                Mail::to($user->email)->send(new Newsletter(
                    $user,
                    $request->subject,
                    $request->content,
                    $request->template
                ));
                $sent++;
            } catch (\Exception $e) {
                Log::error('Failed to send newsletter to ' . $user->email . ': ' . $e->getMessage());
                $errorMessages[] = $user->email . ': ' . $e->getMessage();
                $failed++;
            }
        }

        $message = "Email sent to {$sent} users";
        if ($failed > 0) {
            $message .= " ({$failed} failed)";
        }

        if ($failed === $recipients->count()) {
            return back()->with('error', 'All emails failed. Check logs.')->with('error_details', $errorMessages);
        }

        return back()->with('success', $message);
    }
}