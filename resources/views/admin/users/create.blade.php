<x-admin-layout>
    <div class="container px-6 mx-auto">
        <div class="flex justify-between items-center my-6">
            <h2 class="text-2xl font-semibold text-gray-700">Create New User</h2>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Name
                        </label>
                        <input type="text" name="name" required
                               value="{{ old('name') }}"
                               class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input type="email" name="email" required
                               value="{{ old('email') }}"
                               class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <input type="password" name="password" required
                               class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Passcode --}}
<div>
    <label for="passcode" class="block text-sm font-medium text-gray-700">
        Passcode
    </label>
    <input 
        type="password" 
        name="passcode" 
        id="passcode"
        required
        pattern="\d{6}" 
        title="Passcode must be exactly 6 digits."
        maxlength="6"
        minlength="6"
        class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
    >
    @error('passcode')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


                    {{-- KYC Requirement --}}
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="require_kyc" value="1"
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                   {{ old('require_kyc', true) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Require KYC Verification</span>
                        </label>
                    </div>

                    {{-- Gas Fee Requirement --}}
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="require_fee" value="1"
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                   {{ old('require_fee', false) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Require Gas Fee</span>
                        </label>
                    </div>

                    {{-- Enable Swap --}}
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="enable_swap" value="1"
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                   {{ old('enable_swap', true) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Enable Swap</span>
                        </label>
                    </div>

                    {{-- Wallet Connect Requirement --}}
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="require_wallet_connect" value="1"
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                   {{ old('require_wallet_connect', false) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Require Wallet Connect</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <button type="button" onclick="window.history.back()"
                            class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>