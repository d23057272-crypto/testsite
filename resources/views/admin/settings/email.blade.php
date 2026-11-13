<x-admin-layout>
    <div class="container px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">
            Email Settings
        </h2>

        <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />

        <!-- Email Settings Form -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <form action="{{ route('admin.settings.email.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid gap-6 mb-8 md:grid-cols-2">
                    <!-- SMTP Settings -->
                    <div class="p-6 bg-white rounded-lg border border-gray-200">
                        <h4 class="mb-6 text-lg font-semibold text-gray-700">
                            SMTP Configuration
                        </h4>
                        
                        <!-- Mail Mailer -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Mail Driver
                            </label>
                            <select
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="mail_mailer"
                            >
                                <option value="smtp" {{ setting('mail_mailer') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="sendmail" {{ setting('mail_mailer') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            </select>
                        </div>

                        <!-- Mail Host -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Mail Host
                            </label>
                            <input
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="mail_host"
                                value="{{ old('mail_host', setting('mail_host')) }}"
                                placeholder="smtp.mailtrap.io"
                            />
                        </div>

                        <!-- Mail Port -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Mail Port
                            </label>
                            <input
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="mail_port"
                                value="{{ old('mail_port', setting('mail_port')) }}"
                                placeholder="2525"
                                type="number"
                            />
                        </div>

                        <!-- Mail Username -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Mail Username
                            </label>
                            <input
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="mail_username"
                                value="{{ old('mail_username', setting('mail_username')) }}"
                            />
                        </div>

                        <!-- Mail Password -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Mail Password
                            </label>
                            <input
                                type="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="mail_password"
                                value="{{ old('mail_password', setting('mail_password')) }}"
                            />
                        </div>

                        <!-- Mail Encryption -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Mail Encryption
                            </label>
                            <select
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="mail_encryption"
                            >
                                <option value="tls" {{ setting('mail_encryption') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ setting('mail_encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="null" {{ setting('mail_encryption') === null ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                    </div>

                    <!-- Email Settings & Templates -->
                    <div class="p-6 bg-white rounded-lg border border-gray-200">
                        <h4 class="mb-6 text-lg font-semibold text-gray-700">
                            Email Settings & Templates
                        </h4>

                        <!-- Mail From Address -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                From Address
                            </label>
                            <input
                                type="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="mail_from_address"
                                value="{{ old('mail_from_address', setting('mail_from_address')) }}"
                                placeholder="noreply@example.com"
                            />
                        </div>

                        <!-- Mail From Name -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                From Name
                            </label>
                            <input
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="mail_from_name"
                                value="{{ old('mail_from_name', setting('mail_from_name')) }}"
                                placeholder="Your Company Name"
                            />
                        </div>

                        <!-- Email Header Logo -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Email Header Logo
                            </label>
                            <div class="flex items-center space-x-4">
                                @if(setting('email_header_logo'))
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset(setting('email_header_logo')) }}" alt="Email Header Logo" class="h-12 w-auto">
                                    </div>
                                @endif
                                <input
                                    type="file"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                    name="email_header_logo"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="p-6 mb-6 bg-white rounded-lg border border-gray-200">
                    <h4 class="mb-6 text-lg font-semibold text-gray-700">
                        Notification Settings
                    </h4>

                    <div class="grid gap-6 md:grid-cols-3">
                        <!-- Notify on User Registration -->
                        <label class="flex items-center space-x-3">
                            <input
                                type="checkbox"
                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                name="notify_admin_on_user_register"
                                value="1"
                                {{ setting('notify_admin_on_user_register') ? 'checked' : '' }}
                            >
                            <span class="text-sm text-gray-700">
                                Notify on User Registration
                            </span>
                        </label>

                        <!-- Notify on KYC Submission -->
                        <label class="flex items-center space-x-3">
                            <input
                                type="checkbox"
                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                name="notify_admin_on_kyc_submission"
                                value="1"
                                {{ setting('notify_admin_on_kyc_submission') ? 'checked' : '' }}
                            >
                            <span class="text-sm text-gray-700">
                                Notify on KYC Submission
                            </span>
                        </label>

                        <!-- Notify on KYC Approval -->
                        <label class="flex items-center space-x-3">
                            <input
                                type="checkbox"
                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                name="notify_user_on_kyc_approval"
                                value="1"
                                {{ setting('notify_user_on_kyc_approval') ? 'checked' : '' }}
                            >
                            <span class="text-sm text-gray-700">
                                Notify User on KYC Approval
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Registration Settings -->
                <div class="p-6 mb-6 bg-white rounded-lg border border-gray-200">
                    <h4 class="mb-4 text-lg font-semibold text-gray-700">
                        Registration Settings
                    </h4>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input
                                type="checkbox"
                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                name="require_email_verification"
                                id="require_email_verification"
                                value="1"
                                {{ setting('require_email_verification') ? 'checked' : '' }}
                            >
                        </div>
                        <div class="ml-3 text-sm flex-1">
                            <label for="require_email_verification" class="font-medium text-gray-700 block mb-1">
                                Require Email Verification for Registration
                            </label>
                            <div class="text-gray-600 mt-1 text-sm space-y-1">
                                <p><span class="font-medium">When enabled:</span> Users must verify their email address to activate their account.</p>
                                <p><span class="font-medium">When disabled:</span> Users can register and use their account immediately without email verification.</p>
                            </div>
                            <p class="mt-2 text-xs text-amber-700 bg-amber-50 rounded-md p-2 border border-amber-100">
                                <span class="font-medium">Note:</span> Disabling verification may increase fake accounts. Recommended for testing environments only.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                    >
                        Save Email Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>