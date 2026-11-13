<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Update a setting value
     */
    public function updateSetting(string $key, $value): void
    {
        // Update or create the setting
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear the cache for this setting
        Cache::forget('setting.' . $key);
    }

    /**
     * Upload a file and return its path
     */
    public function uploadFile($file, string $directory): string
    {
        // Generate a unique file name
        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        
        // Store the file and get its path
        $path = $file->storeAs("public/{$directory}", $fileName);
        
        // Delete old file if exists
        $oldFile = Setting::where('key', $directory)->value('value');
        if ($oldFile && Storage::exists("public/{$directory}/" . basename($oldFile))) {
            Storage::delete("public/{$directory}/" . basename($oldFile));
        }
        
        return $path;
    }

    /**
     * Update Laravel's mail configuration
     */
    public function updateMailConfig(array $config): void
    {
        // Update the mail configuration
        Config::set('mail.default', $config['mail_mailer']);
        Config::set('mail.mailers.smtp.host', $config['mail_host']);
        Config::set('mail.mailers.smtp.port', $config['mail_port']);
        Config::set('mail.mailers.smtp.username', $config['mail_username']);
        
        // Only update password if provided
        if (!empty($config['mail_password'])) {
            Config::set('mail.mailers.smtp.password', $config['mail_password']);
        }
        
        Config::set('mail.mailers.smtp.encryption', $config['mail_encryption'] === 'null' ? null : $config['mail_encryption']);
        Config::set('mail.from.address', $config['mail_from_address']);
        Config::set('mail.from.name', $config['mail_from_name']);
    }

    /**
     * Get all settings by group
     */
    public function getSettingsByGroup(string $group): array
    {
        return Setting::where('group', $group)
            ->orderBy('order')
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Test email configuration
     */
    public function testEmailConfiguration(): bool
    {
        try {
            // Attempt to connect to the mail server
            $transport = Config::get('mail.mailers.' . Config::get('mail.default'));
            $dsn = sprintf(
                '%s://%s:%s@%s:%d',
                $transport['encryption'] ?? 'tcp',
                $transport['username'],
                $transport['password'],
                $transport['host'],
                $transport['port']
            );

            $stream = stream_socket_client($dsn, $errno, $errstr, 5);
            if (!$stream) {
                throw new \Exception($errstr);
            }
            fclose($stream);
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}