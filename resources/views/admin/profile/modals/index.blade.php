<!-- Profile Management Modal -->
<div id="profileModal-{{ $user->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 overflow-hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b shrink-0">
                <h3 class="text-lg font-medium text-gray-900">Manage Profile</h3>
                <button onclick="document.getElementById('profileModal-{{ $user->id }}').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-500">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div class="overflow-y-auto flex-grow px-6 py-4">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- UUID -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            UUID
                        </label>
                        <input type="text" name="uuid"
                               value="{{ old('uuid', $user->uuid) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Name
                        </label>
                        <input type="text" name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    
                    <!-- Passcode -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Change Passcode</label>
                        <input type="text" name="passcode"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               pattern="\d{6}" maxlength="6" minlength="6" title="Passcode must be exactly 6 digits">
                    </div>

                    <!-- KYC Status -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            KYC Status
                        </label>
                        <select name="kyc_status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="not_submitted" {{ $user->kyc_status === 'not_submitted' ? 'selected' : '' }}>
                                Not Submitted
                            </option>
                            <option value="pending" {{ $user->kyc_status === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="approved" {{ $user->kyc_status === 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>
                            <option value="rejected" {{ $user->kyc_status === 'rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>
                        </select>
                    </div>

                    <!-- KYC Fee Amount -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            KYC Fee Amount
                        </label>
                        <input type="number" name="kyc_fee_amount" step="0.01"
                               value="{{ old('kyc_fee_amount', $user->kyc_fee_amount) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>



                    <!-- Toggles -->
                    <div class="space-y-4 mb-4">
                        <!-- Email Verified Toggle -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="email_verified"
                                   value="1"
                                   {{ !is_null($user->email_verified_at) ? 'checked' : '' }}
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">Email Verified</span>
                        </label>
                        
                        <!-- Require KYC Toggle -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="require_kyc"
                                   value="1"
                                   {{ $user->require_kyc ? 'checked' : '' }}
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">Require KYC</span>
                        </label>

                        <!-- KYC Fee Toggle -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="kyc_fee"
                                   value="1"
                                   {{ $user->kyc_fee ? 'checked' : '' }}
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">KYC Fee</span>
                        </label>
                        
                        <!-- Swap Fee Toggle -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="swap_fee"
                                   value="1"
                                   {{ $user->swap_fee ? 'checked' : '' }}
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">Swap Fee</span>
                        </label>

                        <!-- Enable Crypto Sending Toggle -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="enable_crypto_sending"
                                   value="1"
                                   {{ $user->enable_crypto_sending ? 'checked' : '' }}
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">Enable Crypto Sending</span>
                        </label>

                        <!-- Enable Swap Toggle -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="enable_swap"
                                   value="1"
                                   {{ $user->enable_swap ? 'checked' : '' }}
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">Enable Swap</span>
                        </label>
                        
                        <!-- Require Gas Fee Toggle -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="require_fee"
                                   value="1"
                                   {{ $user->require_fee ? 'checked' : '' }}
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">Require Gas Fee</span>
                        </label>
                        
                        <!-- Require Wallet Connect Toggle -->
                        <label class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="require_wallet_connect"
                                   value="1"
                                   {{ $user->require_wallet_connect ? 'checked' : '' }}
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">Require Wallet Connect</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="document.getElementById('profileModal-{{ $user->id }}').classList.add('hidden')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>