<x-admin-layout>
    <div class="container p-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">
            Settings
        </h2>

        <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />

        <!-- General Settings Form -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid gap-6 mb-8 md:grid-cols-2">
                    <!-- Company Information -->
                    <div class="p-6 bg-white rounded-lg border border-gray-200">
                        <h4 class="mb-6 text-lg font-semibold text-gray-700">
                            Company Information
                        </h4>
                        
                        <!-- Site Name -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Site Name
                            </label>
                            <input
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="site_name"
                                value="{{ old('site_name', setting('site_name')) }}"
                                placeholder="Your Site Name"
                            />
                        </div>

                        <!-- Company Name -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Company Name
                            </label>
                            <input
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="company_name"
                                value="{{ old('company_name', setting('company_name')) }}"
                                placeholder="Your Company Name"
                            />
                        </div>

                        <!-- Company Address -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Company Address
                            </label>
                            <textarea
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="company_address"
                                rows="3"
                                placeholder="Enter company address"
                            >{{ old('company_address', setting('company_address')) }}</textarea>
                        </div>

                        <!-- Company Phone -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Company Phone
                            </label>
                            <input
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="company_phone"
                                value="{{ old('company_phone', setting('company_phone')) }}"
                                placeholder="Your Company Phone"
                            />
                        </div>

                        <!-- Company Email -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Company Email
                            </label>
                            <input
                                type="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                name="company_email"
                                value="{{ old('company_email', setting('company_email')) }}"
                                placeholder="company@example.com"
                            />
                        </div>
                    </div>

                    <!-- Logo Settings -->
                    <div class="p-6 bg-white rounded-lg border border-gray-200">
                        <h4 class="mb-6 text-lg font-semibold text-gray-700">
                            Logo Settings
                        </h4>

                        <!-- Site Logo -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Site Logo
                            </label>
                            <div class="flex items-center space-x-4">
                                @if(setting('site_logo'))
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset(setting('site_logo')) }}" alt="Current Logo" class="h-12 w-auto">
                                    </div>
                                @endif
                                <input
                                    type="file"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                    name="site_logo"
                                />
                            </div>
                        </div>

                        <!-- Site Favicon -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Site Favicon
                            </label>
                            <div class="flex items-center space-x-4">
                                @if(setting('site_favicon'))
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset(setting('site_favicon')) }}" alt="Current Favicon" class="h-8 w-auto">
                                    </div>
                                @endif
                                <input
                                    type="file"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                                    name="site_favicon"
                                />
                            </div>
                        </div>
                    </div>
                </div>
        <!-- New Referral Settings Section -->
            <div class="p-6 bg-white rounded-lg border border-gray-200">
                <h4 class="mb-6 text-lg font-semibold text-gray-700">
                    Referral Settings
                </h4>

                <!-- Enable/Disable Referral System -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Enable Referral System
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="referral_enabled" class="sr-only peer" value="1" 
                               {{ old('referral_enabled', setting('referral_enabled')) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 
                                  dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 
                                  peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] 
                                  after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 
                                  after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                                  peer-checked:bg-purple-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">
                            {{ setting('referral_enabled') ? 'Enabled' : 'Disabled' }}
                        </span>
                    </label>
                </div>

                <!-- Referral Bonus Amount -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Referral Bonus Amount (USD)
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full pl-8 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                            name="referral_bonus_amount"
                            value="{{ old('referral_bonus_amount', setting('referral_bonus_amount', '0.00')) }}"
                            placeholder="0.00"
                        />
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Amount awarded to referrer for each successful referral</p>
                </div>

                <!-- Bonus Distribution -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Bonus Distribution
                    </label>
                    <select
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white text-gray-700"
                        name="referral_bonus_type"
                    >
                        <option value="instant" {{ setting('referral_bonus_type') === 'instant' ? 'selected' : '' }}>
                            Instant Bonus
                        </option>
                        <option value="after_kyc" {{ setting('referral_bonus_type') === 'after_kyc' ? 'selected' : '' }}>
                            After KYC Verification
                        </option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">When the referral bonus should be awarded</p>
                </div>
            </div>
        </div>


                <!-- Submit Button -->
                <div class="flex justify-end mt-6 mb-6">
                    <button
                        type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                    >
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>