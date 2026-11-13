<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Cryptocurrency; // Import for Crypto Management
use App\Services\CryptoService; // Import for Price Display
use Illuminate\Support\Facades\Cache; // Import for Cache Clearing
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Display general settings form
     */
    public function general()
    {
        return view('admin.settings.general');
    }

    /**
     * Display email settings form
     */
    public function email()
    {
        return view('admin.settings.email');
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'referral_enabled' => 'nullable|boolean',
            'referral_bonus_amount' => 'required_if:referral_enabled,1|numeric|min:0',
            'referral_bonus_type' => 'required_if:referral_enabled,1|string|in:instant,after_kyc',
        ]);

        try {
           if ($request->hasFile('site_logo')) {
                // Delete old file if exists
                $oldLogo = Setting::where('key', 'site_logo')->value('value');
                if ($oldLogo && File::exists(public_path($oldLogo))) {
                    File::delete(public_path($oldLogo));
                }

                $logoPath = $this->handleFileUpload($request->file('site_logo'), 'uploads/logos');
                Setting::updateOrCreate(
                    ['key' => 'site_logo'],
                    [
                        'value' => $logoPath,
                        'group' => 'general',
                        'type' => 'file',
                        'display_name' => 'Site Logo',
                        'is_public' => true
                    ]
                );
            }

            if ($request->hasFile('site_favicon')) {
                // Delete old file if exists
                $oldFavicon = Setting::where('key', 'site_favicon')->value('value');
                if ($oldFavicon && File::exists(public_path($oldFavicon))) {
                    File::delete(public_path($oldFavicon));
                }

                $faviconPath = $this->handleFileUpload($request->file('site_favicon'), 'uploads/favicon');
                Setting::updateOrCreate(
                    ['key' => 'site_favicon'],
                    [
                        'value' => $faviconPath,
                        'group' => 'general',
                        'type' => 'file',
                        'display_name' => 'Site Favicon',
                        'is_public' => true
                    ]
                );
            }

            // Update other settings
            $settings = [
                'site_name' => [
                    'value' => $validated['site_name'],
                    'group' => 'general',
                    'type' => 'text',
                    'display_name' => 'Site Name',
                    'is_public' => true
                ],
                'company_name' => [
                    'value' => $validated['company_name'],
                    'group' => 'general',
                    'type' => 'text',
                    'display_name' => 'Company Name',
                    'is_public' => true
                ],
                'company_address' => [
                    'value' => $validated['company_address'],
                    'group' => 'general',
                    'type' => 'textarea',
                    'display_name' => 'Company Address',
                    'is_public' => true
                ],
                'company_phone' => [
                    'value' => $validated['company_phone'],
                    'group' => 'general',
                    'type' => 'text',
                    'display_name' => 'Company Phone',
                    'is_public' => true
                ],
                'company_email' => [
                    'value' => $validated['company_email'],
                    'group' => 'general',
                    'type' => 'email',
                    'display_name' => 'Company Email',
                    'is_public' => true
                ],
                'referral_enabled' => [
                    'value' => $request->has('referral_enabled') ? '1' : '0',
                    'group' => 'referral',
                    'type' => 'boolean',
                    'display_name' => 'Enable Referral System',
                    'is_public' => true
                ],
                'referral_bonus_amount' => [
                    'value' => $request->input('referral_bonus_amount', '0.00'),
                    'group' => 'referral',
                    'type' => 'number',
                    'display_name' => 'Referral Bonus Amount',
                    'is_public' => true
                ],
                'referral_bonus_type' => [
                    'value' => $request->input('referral_bonus_type', 'instant'),
                    'group' => 'referral',
                    'type' => 'select',
                    'display_name' => 'Referral Bonus Distribution Type',
                    'is_public' => true
                ]
            ];

            foreach ($settings as $key => $data) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    $data
                );
            }

            return redirect()->back()->with('status', 'General settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Settings Update Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()]);
        }
    }
    
    private function handleFileUpload($file, $directory)
    {
        // Create directory if it doesn't exist
        $path = public_path($directory);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Generate unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Move file to directory
        $file->move($path, $filename);
        
        // Return relative path
        return $directory . '/' . $filename;
    }

    /**
     * Sync wallet addresses from JSON to database
     */
    public function syncWalletAddresses()
    {
        try {
            $jsonPath = storage_path('app/wallet-addresses.json');
            
            // Check if JSON file exists
            if (!File::exists($jsonPath)) {
                Log::error('Wallet addresses JSON file not found!');
                return response()->json(['error' => 'Wallet addresses file not found'], 404);
            }

            // Read JSON file
            $jsonAddresses = json_decode(File::get($jsonPath), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON file');
            }

            // Get current database values
            $dbAddresses = Setting::where('group', 'wallets')
                                 ->pluck('value', 'key')
                                 ->toArray();

            $synced = false;
            
            // Compare and sync differences
            foreach ($jsonAddresses as $key => $address) {
                $dbValue = $dbAddresses[$key] ?? null;
                
                if ($dbValue !== $address) {
                    Setting::updateOrCreate(
                        ['key' => $key],
                        [
                            'value' => $address,
                            'group' => 'wallets',
                            'type' => 'text',
                            'display_name' => ucfirst(str_replace('_', ' ', $key)),
                            'is_public' => false
                        ]
                    );
                    
                    Log::info("Wallet address synced: {$key}");
                    $synced = true;
                }
            }

            return response()->json([
                'success' => true,
                'message' => $synced ? 'Wallet addresses synchronized successfully' : 'No synchronization needed',
                'synced' => $synced
            ]);
            
        } catch (\Exception $e) {
            Log::error('Wallet Sync Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to sync wallet addresses: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display wallet settings form
     * FIXED: Dynamically fetches address keys based on DB cryptos
     */
    public function wallets()
    {
        // Get all cryptocurrencies from the catalog.
        $cryptos = Cryptocurrency::orderBy('name')->get();
        
        // Build network-aware address keys (e.g., btc_address, usdt_trc20_address).
        $addressKeys = $cryptos->map(function($crypto) {
            return $crypto->wallet_address_setting_key;
        })->unique()->values()->toArray();

        // Fetch settings for those keys.
        $settings = Setting::whereIn('key', $addressKeys)
                           ->pluck('value', 'key')
                           ->toArray();

        // Get live prices.
        $livePrices = app(CryptoService::class)->getPrices();

        return view('admin.settings.wallets', compact('settings', 'cryptos', 'livePrices'));
    }

    /**
     * Update wallet addresses
     * FIXED: Dynamically validates only existing crypto keys to allow saving new wallets
     */
    public function updateWallets(Request $request)
    {
        // Build dynamic validation rules from the catalog.
        $cryptos = Cryptocurrency::all();
        $rules = [];
        
        foreach ($cryptos as $crypto) {
            $key = $crypto->wallet_address_setting_key;
            $rules[$key] = 'nullable|string|max:255';
        }

        // Validate only keys represented by catalog entries.
        $validated = $request->validate($rules);

        try {
            // Update database settings
            foreach ($validated as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'group' => 'wallets',
                        'type' => 'text',
                        'display_name' => ucfirst(str_replace('_', ' ', $key)) . ' Address',
                        'is_public' => false
                    ]
                );
            }

            // Prepare the synchronized address data for the legacy JSON file.
            $currentSettings = Setting::whereIn('key', array_keys($rules))->pluck('value', 'key')->toArray();
            
            // Store in JSON file
            $jsonPath = storage_path('app/wallet-addresses.json');
            File::put($jsonPath, json_encode($currentSettings, JSON_PRETTY_PRINT));

            return redirect()->back()->with('status', 'Wallet addresses updated successfully.');
        } catch (\Exception $e) {
            Log::error('Wallet Settings Update Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to update wallet settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Update email settings
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|string|in:smtp,sendmail',
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_username' => 'required|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'required|string|in:tls,ssl,null',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'email_header_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email_footer_text' => 'nullable|string',
            'email_signature' => 'nullable|string',
            'notify_admin_on_user_register' => 'nullable|boolean',
            'notify_admin_on_kyc_submission' => 'nullable|boolean',
            'notify_user_on_kyc_approval' => 'nullable|boolean',
        ]);

        try {
            // Handle email header logo upload
            if ($request->hasFile('email_header_logo')) {
                // Delete old file if exists
                $oldLogo = Setting::where('key', 'email_header_logo')->value('value');
                if ($oldLogo && File::exists(public_path($oldLogo))) {
                    File::delete(public_path($oldLogo));
                }

                // Upload new file
                $path = public_path('uploads/email');
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }

                $file = $request->file('email_header_logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);
                
                $logoPath = 'uploads/email/' . $filename;

                Setting::updateOrCreate(
                    ['key' => 'email_header_logo'],
                    [
                        'value' => $logoPath,
                        'group' => 'email',
                        'type' => 'file',
                        'display_name' => 'Email Header Logo',
                        'is_public' => true
                    ]
                );
            }
            
            // Email settings mapping
            $settings = [
                'mail_mailer' => [
                    'group' => 'email',
                    'type' => 'text',
                    'display_name' => 'Mail Mailer',
                    'is_public' => false
                ],
                'mail_host' => [
                    'group' => 'email',
                    'type' => 'text',
                    'display_name' => 'Mail Host',
                    'is_public' => false
                ],
                'mail_port' => [
                    'group' => 'email',
                    'type' => 'text',
                    'display_name' => 'Mail Port',
                    'is_public' => false
                ],
                'mail_username' => [
                    'group' => 'email',
                    'type' => 'text',
                    'display_name' => 'Mail Username',
                    'is_public' => false
                ],
                'mail_encryption' => [
                    'group' => 'email',
                    'type' => 'text',
                    'display_name' => 'Mail Encryption',
                    'is_public' => false
                ],
                'mail_from_address' => [
                    'group' => 'email',
                    'type' => 'email',
                    'display_name' => 'Mail From Address',
                    'is_public' => false
                ],
                'mail_from_name' => [
                    'group' => 'email',
                    'type' => 'text',
                    'display_name' => 'Mail From Name',
                    'is_public' => false
                ]
            ];

            // Update email settings
            foreach ($settings as $key => $data) {
                if (isset($validated[$key])) {
                    Setting::updateOrCreate(
                        ['key' => $key],
                        array_merge($data, ['value' => $validated[$key]])
                    );
                }
            }

            // Update password only if provided
            if (!empty($validated['mail_password'])) {
                Setting::updateOrCreate(
                    ['key' => 'mail_password'],
                    [
                        'value' => $validated['mail_password'],
                        'group' => 'email',
                        'type' => 'password',
                        'display_name' => 'Mail Password',
                        'is_public' => false
                    ]
                );
            }

            // ✅ CRITICAL: Save require_email_verification setting
            Setting::updateOrCreate(
                ['key' => 'require_email_verification'],
                [
                    'value' => $request->has('require_email_verification') ? '1' : '0',
                    'group' => 'email',
                    'type' => 'boolean',
                    'display_name' => 'Require Email Verification',
                    'is_public' => false
                ]
            );

            // Update notification settings
            $notificationSettings = [
                'notify_admin_on_user_register',
                'notify_admin_on_kyc_submission',
                'notify_user_on_kyc_approval'
            ];

            foreach ($notificationSettings as $setting) {
                Setting::updateOrCreate(
                    ['key' => $setting],
                    [
                        'value' => $request->has($setting) ? '1' : '0',
                        'group' => 'notifications',
                        'type' => 'boolean',
                        'display_name' => ucwords(str_replace('_', ' ', $setting)),
                        'is_public' => false
                    ]
                );
            }

            return redirect()->back()->with('status', 'Email settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Email Settings Update Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to update email settings: ' . $e->getMessage()]);
        }
    }
}