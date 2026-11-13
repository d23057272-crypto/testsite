<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Your Site Name',
                'group' => 'general',
                'type' => 'text',
                'display_name' => 'Site Name',
                'description' => 'The name of your website',
                'is_public' => true,
                'order' => 1,
            ],
            [
                'key' => 'company_name',
                'value' => 'Your Company Name',
                'group' => 'general',
                'type' => 'text',
                'display_name' => 'Company Name',
                'description' => 'Your company\'s legal name',
                'is_public' => true,
                'order' => 2,
            ],
            [
                'key' => 'company_address',
                'value' => 'Your Company Address',
                'group' => 'general',
                'type' => 'textarea',
                'display_name' => 'Company Address',
                'description' => 'Your company\'s official address',
                'is_public' => true,
                'order' => 3,
            ],
            [
                'key' => 'company_phone',
                'value' => '+1234567890',
                'group' => 'general',
                'type' => 'text',
                'display_name' => 'Company Phone',
                'description' => 'Your company\'s contact number',
                'is_public' => true,
                'order' => 4,
            ],
            [
                'key' => 'company_email',
                'value' => 'contact@example.com',
                'group' => 'general',
                'type' => 'text',
                'display_name' => 'Company Email',
                'description' => 'Your company\'s contact email',
                'is_public' => true,
                'order' => 5,
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'group' => 'general',
                'type' => 'file',
                'display_name' => 'Site Logo',
                'description' => 'Your website logo (recommended size: 200x50px)',
                'is_public' => true,
                'order' => 6,
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'group' => 'general',
                'type' => 'file',
                'display_name' => 'Site Favicon',
                'description' => 'Your website favicon (recommended size: 32x32px)',
                'is_public' => true,
                'order' => 7,
            ],
            
            // Email Settings
            [
                'key' => 'mail_mailer',
                'value' => 'smtp',
                'group' => 'email',
                'type' => 'text',
                'display_name' => 'Mail Mailer',
                'description' => 'Mail sending driver (smtp, sendmail, etc.)',
                'is_public' => false,
                'order' => 1,
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
                'group' => 'email',
                'type' => 'text',
                'display_name' => 'Mail Host',
                'description' => 'SMTP host address',
                'is_public' => false,
                'order' => 2,
            ],
            [
                'key' => 'mail_port',
                'value' => '2525',
                'group' => 'email',
                'type' => 'text',
                'display_name' => 'Mail Port',
                'description' => 'SMTP port number',
                'is_public' => false,
                'order' => 3,
            ],
            [
                'key' => 'mail_username',
                'value' => null,
                'group' => 'email',
                'type' => 'text',
                'display_name' => 'Mail Username',
                'description' => 'SMTP username',
                'is_public' => false,
                'order' => 4,
            ],
            [
                'key' => 'mail_password',
                'value' => null,
                'group' => 'email',
                'type' => 'password',
                'display_name' => 'Mail Password',
                'description' => 'SMTP password',
                'is_public' => false,
                'order' => 5,
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'group' => 'email',
                'type' => 'text',
                'display_name' => 'Mail Encryption',
                'description' => 'SMTP encryption (tls/ssl)',
                'is_public' => false,
                'order' => 6,
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@example.com',
                'group' => 'email',
                'type' => 'text',
                'display_name' => 'Mail From Address',
                'description' => 'Default from email address',
                'is_public' => false,
                'order' => 7,
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'Your Company Name',
                'group' => 'email',
                'type' => 'text',
                'display_name' => 'Mail From Name',
                'description' => 'Default sender name',
                'is_public' => false,
                'order' => 8,
            ],
            
            // Notification Settings
            [
                'key' => 'notify_admin_on_user_register',
                'value' => '1',
                'group' => 'notifications',
                'type' => 'boolean',
                'display_name' => 'Notify on User Registration',
                'description' => 'Send email notification when new user registers',
                'is_public' => false,
                'order' => 1,
            ],
            [
                'key' => 'notify_admin_on_kyc_submission',
                'value' => '1',
                'group' => 'notifications',
                'type' => 'boolean',
                'display_name' => 'Notify on KYC Submission',
                'description' => 'Send email notification when user submits KYC',
                'is_public' => false,
                'order' => 2,
            ],
            [
                'key' => 'notify_user_on_kyc_approval',
                'value' => '1',
                'group' => 'notifications',
                'type' => 'boolean',
                'display_name' => 'Notify on KYC Approval',
                'description' => 'Send email notification when KYC is approved',
                'is_public' => false,
                'order' => 3,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}