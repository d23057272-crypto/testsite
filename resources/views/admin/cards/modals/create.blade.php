<!-- Create Card Modal -->
<div id="createCardModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Create New Card</h3>
                <button onclick="closeCreateCardModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.cards.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="crypto_asset_id" value="{{ $cryptoAsset->id }}">

                <div class="grid grid-cols-2 gap-6">
                    <!-- Card Holder Name -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Card Holder Name</label>
                        <input type="text" name="card_holder" required 
                               value="{{ old('card_holder', $user->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <!-- Card Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Card Type</label>
                        <select name="card_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="virtual">Virtual Card</option>
                            <option value="physical">Physical Card</option>
                        </select>
                    </div>

                    <!-- Linked Crypto Asset -->

                    <!-- Expiry Month -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Month</label>
                        <select name="expiry_month" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Expiry Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Year</label>
                        <select name="expiry_year" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- CVV -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                        <input type="text" name="cvv" required
                               pattern="[0-9]{3,4}" maxlength="4"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Enter 3 or 4 digit CVV">
                    </div>

                    <!-- Billing Address -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                        <textarea name="billing_address" required rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>

                    <!-- ZIP Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code</label>
                        <input type="text" name="zip_code" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <!-- Initial Balance -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Initial Balance</label>
                        <input type="number" name="balance" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="0.00">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateCardModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                        Create Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>