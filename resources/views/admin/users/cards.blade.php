<x-admin-layout>
    <div class="container px-6 mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center my-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-700">
                    {{ $user->name }}'s Cards
                </h2>
                <p class="text-sm text-gray-500">ID: {{ $user->uuid }}</p>
            </div>
            
            <button onclick="openCreateCardModal()"
                    class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <i class="ri-bank-card-line mr-2"></i>Create Card
            </button>
        </div>

       <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            @forelse($user->cards as $card)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 bg-gradient-to-r from-purple-500 to-purple-600">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-white text-lg font-medium mb-1">
                                    •••• •••• •••• {{ $card->last_four }}
                                </p>
                                <p class="text-purple-100">{{ $card->card_holder }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($card->status === 'active') bg-green-100 text-green-800
                                    @elseif($card->status === 'frozen') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($card->status) }}
                                </span>
                                <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                                    {{ ucfirst($card->card_type) }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-purple-100 text-sm">Balance</p>
                            <p class="text-white text-2xl font-bold">${{ number_format($card->balance, 2) }}</p>
                        </div>
                    </div>

                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Expiry Date</p>
                                <p class="font-medium">{{ $card->expiry_month }}/{{ $card->expiry_year }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Billing Address</p>
                                <p class="font-medium">{{ $card->billing_address }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">ZIP Code</p>
                                <p class="font-medium">{{ $card->zip_code }}</p>
                            </div>
                        </div>

                        <!-- Card Actions -->
                       <!-- Card Actions in your cards view -->
<div class="border-t pt-4">
    <div class="flex justify-between items-center">
        <div class="flex space-x-2">
            <button onclick="openFundCardModal('{{ $card->id }}')"
                    class="px-3 py-1 text-xs text-white bg-green-600 rounded hover:bg-green-700">
                Add Funds
            </button>
            <button onclick="openSubtractCardModal('{{ $card->id }}')"
                    class="px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                Subtract Funds
            </button>
        </div>
        <div class="flex space-x-2">
            @if($card->status === 'active')
                <form action="{{ route('admin.cards.freeze', $card) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">
                        Freeze Card
                    </button>
                </form>
                <form action="{{ route('admin.cards.deactivate', $card) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 text-xs text-white bg-gray-600 rounded hover:bg-gray-700">
                        Deactivate Card
                    </button>
                </form>
            @elseif($card->status === 'frozen')
                <form action="{{ route('admin.cards.unfreeze', $card) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 text-xs text-white bg-purple-600 rounded hover:bg-purple-700">
                        Unfreeze Card
                    </button>
                </form>
            @elseif($card->status === 'inactive')
                <form action="{{ route('admin.cards.activate', $card) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 text-xs text-white bg-green-600 rounded hover:bg-green-700">
                        Activate Card
                    </button>
                </form>
            @endif
            
            <form action="{{ route('admin.cards.destroy', $card) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this card?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
                    </div>

                    <!-- Card Transactions -->
                    <div class="border-t">
                        <div class="p-4">
                            <h4 class="font-medium text-gray-700">Recent Transactions</h4>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-500 text-left">Date</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-500 text-left">Description</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-500 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse($card->transactions()->latest()->take(5)->get() as $transaction)
                                        <tr>
                                            <td class="px-4 py-2 text-sm">
                                                {{ $transaction->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                {{ $transaction->description }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-right">
                                                <span class="{{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    ${{ number_format($transaction->amount, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-4 text-sm text-center text-gray-500">
                                                No transactions found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-500">No cards found for this user</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Card Modal -->
    @include('admin.cards.modals.create')

    <!-- Fund Card Modal -->
    @include('admin.cards.modals.fund')

    <!-- Subtract Card Modal -->
    @include('admin.cards.modals.subtract')

</x-admin-layout>
<script>
    function openCreateCardModal() {
        document.getElementById('createCardModal').classList.remove('hidden');
    }

    function closeCreateCardModal() {
        document.getElementById('createCardModal').classList.add('hidden');
    }

    function openFundCardModal(cardId) {
        const modal = document.getElementById('fundCardModal');
        const form = document.getElementById('fundCardForm');
        form.action = `/admin/cards/${cardId}/fund`;
        modal.classList.remove('hidden');
    }

    function closeFundCardModal() {
        document.getElementById('fundCardModal').classList.add('hidden');
    }

    function openSubtractCardModal(cardId) {
        const modal = document.getElementById('subtractCardModal');
        const form = document.getElementById('subtractCardForm');
        form.action = `/admin/cards/${cardId}/subtract`;
        modal.classList.remove('hidden');
    }

    function closeSubtractCardModal() {
        document.getElementById('subtractCardModal').classList.add('hidden');
    }
</script>