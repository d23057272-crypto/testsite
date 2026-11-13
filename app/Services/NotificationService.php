<?php

namespace App\Services;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send Discord alert for new ticket
     */
    public function notifyNewTicket(User $user, string $subject, int $ticketId): void
    {
        try {
            $webhookUrl = config('services.discord.webhook_url');

            if (empty($webhookUrl)) {
                Log::info('Discord webhook not configured. Skipping Discord notification.');
                return;
            }

            $payload = [
                'embeds' => [
                    [
                        'title' => 'New Support Ticket',
                        'description' => "A new support ticket has been created.",
                        'color' => 3447003,
                        'fields' => [
                            [
                                'name' => 'Ticket ID',
                                'value' => "#{$ticketId}",
                                'inline' => true
                            ],
                            [
                                'name' => 'Subject',
                                'value' => $subject,
                                'inline' => true
                            ],
                            [
                                'name' => 'User',
                                'value' => "{$user->name} ({$user->email})",
                                'inline' => false
                            ]
                        ],
                        'timestamp' => now()->toIso8601String()
                    ]
                ]
            ];

            $ch = curl_init($webhookUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_exec($ch);
            curl_close($ch);

        } catch (\Exception $e) {
            Log::error('Discord notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Create admin notifications in database.
     * Supports: Spatie roles, is_admin flag, or dedicated Admin model.
     */
    public function notifyAdminsAboutTicket(User $user, string $subject, Ticket $ticket): void
    {
        try {
            $admins = $this->getAdmins();

            foreach ($admins as $admin) {
                $this->createAdminNotification(
                    $admin->id,
                    'New Support Ticket',
                    "User {$user->name} ({$user->email}) opened ticket #{$ticket->ticket_id}: {$subject}",
                    route('admin.tickets.show', $ticket->id)
                );
            }
        } catch (\Exception $e) {
            Log::error('Admin notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Get admin users. Tries multiple strategies:
     * 1. Spatie laravel-permission: User::role('admin')
     * 2. is_admin column on users table
     * 3. Dedicated Admin model
     * 4. Falls back to all users (last resort)
     */
    private function getAdmins()
    {
        // Strategy 1: Spatie laravel-permission
        if (class_exists('Spatie\Permission\Models\Role') && method_exists(User::class, 'role')) {
            try {
                return User::role('admin')->get();
            } catch (\Exception $e) {
                Log::debug('Spatie role query failed, trying next strategy.');
            }
        }

        // Strategy 2: is_admin column
        if (\Schema::hasColumn('users', 'is_admin')) {
            return User::where('is_admin', true)->get();
        }

        // Strategy 3: Dedicated Admin model
        $adminClass = 'App\\Models\\Admin';
        if (class_exists($adminClass)) {
            $admins = $adminClass::all();
            // If Admin model has user_id relation, fetch User models
            if ($admins->isNotEmpty() && isset($admins->first()->user_id)) {
                $userIds = $admins->pluck('user_id')->toArray();
                return User::whereIn('id', $userIds)->get();
            }
            return $admins;
        }

        // Strategy 4: Fallback - notify first user (better than nothing)
        Log::warning('No admin detection strategy worked. Falling back to first user.');
        return User::take(1)->get();
    }

    /**
     * Create admin notification in database
     */
    public function createAdminNotification(int $adminId, string $title, string $message, ?string $url = null): void
    {
        try {
            Notification::create([
                'user_id' => $adminId,
                'user_type' => 'admin',
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Admin notification creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Create user notification in database
     */
    public function createUserNotification(int $userId, string $title, string $message, ?string $url = null): void
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'user_type' => 'user',
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('User notification creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Send email notification (using your premium templates)
     */
    public function sendEmail(string $to, string $mailableClass, array $params = []): void
    {
        try {
            if (!class_exists($mailableClass)) {
                Log::warning("Mailable class {$mailableClass} not found.");
                return;
            }

            $reflection = new \ReflectionClass($mailableClass);
            $instance = $reflection->newInstanceArgs($params);

            \Illuminate\Support\Facades\Mail::to($to)->send($instance);

        } catch (\Exception $e) {
            Log::error('Email notification failed: ' . $e->getMessage());
        }
    }
}