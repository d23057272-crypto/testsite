<x-admin-layout>
   <div class="container px-6 mx-auto">
       <div class="flex justify-between items-center my-6">
           <h2 class="text-2xl font-semibold text-gray-700">Trade History</h2>
       </div>

       <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />

       <!-- Stats -->
       <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
           <div class="bg-white rounded-lg shadow-md p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-blue-100">
                       <i class="ri-exchange-line text-2xl text-blue-500"></i>
                   </div>
                   <div class="ml-4">
                       <p class="text-gray-500 text-sm">Total Trades</p>
                       <p class="text-xl font-semibold">{{ $trades->total() }}</p>
                   </div>
               </div>
           </div>
           
           <div class="bg-white rounded-lg shadow-md p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-green-100">
                       <i class="ri-arrow-up-circle-line text-2xl text-green-500"></i>
                   </div>
                   <div class="ml-4">
                       <p class="text-gray-500 text-sm">Win Rate</p>
                       <p class="text-xl font-semibold">
                           {{ number_format(($trades->where('result', 'win')->count() / max($trades->count(), 1)) * 100, 1) }}%
                       </p>
                   </div>
               </div>
           </div>
           
           <div class="bg-white rounded-lg shadow-md p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-green-100">
                       <i class="ri-money-dollar-circle-line text-2xl text-green-500"></i>
                   </div>
                   <div class="ml-4">
                       <p class="text-gray-500 text-sm">Total Profit</p>
                       <p class="text-xl font-semibold">${{ number_format($trades->sum('profit'), 2) }}</p>
                   </div>
               </div>
           </div>
           
           <div class="bg-white rounded-lg shadow-md p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-red-100">
                       <i class="ri-arrow-down-circle-line text-2xl text-red-500"></i>
                   </div>
                   <div class="ml-4">
                       <p class="text-gray-500 text-sm">Total Loss</p>
                       <p class="text-xl font-semibold">${{ number_format($trades->where('result', 'loss')->sum('amount'), 2) }}</p>
                   </div>
               </div>
           </div>
       </div>

       <!-- Trades Table -->
       <div class="bg-white rounded-lg shadow-md overflow-hidden">
           <div class="overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                   <thead>
                       <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                           <th class="px-4 py-3">Bot/Date</th>
                           <th class="px-4 py-3">Trading Pair</th>
                           <th class="px-4 py-3">Action</th>
                           <th class="px-4 py-3">Amount</th>
                           <th class="px-4 py-3">Price</th>
                           <th class="px-4 py-3">Result</th>
                           <th class="px-4 py-3">Profit/Loss</th>
                           <th class="px-4 py-3">Actions</th>
                       </tr>
                   </thead>
                   <tbody class="bg-white divide-y">
                       @forelse($trades as $trade)
                           <tr class="text-gray-700">
                               <td class="px-4 py-3">
                                   <div class="flex flex-col">
                                       <span class="font-medium">{{ $trade->bot->name }}</span>
                                       <span class="text-sm text-gray-500">{{ $trade->created_at->format('M d, Y H:i') }}</span>
                                   </div>
                               </td>
                               <td class="px-4 py-3">{{ $trade->trading_pair }}</td>
                               <td class="px-4 py-3">
                                   <span class="px-2 py-1 text-xs rounded-full 
                                       {{ $trade->action === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                       {{ ucfirst($trade->action) }}
                                   </span>
                               </td>
                               <td class="px-4 py-3">${{ number_format($trade->amount, 2) }}</td>
                               <td class="px-4 py-3">${{ number_format($trade->price, 2) }}</td>
                               <td class="px-4 py-3">
                                   @if($trade->result === null)
                                       <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                           Pending
                                       </span>
                                   @else
                                       <span class="px-2 py-1 text-xs rounded-full 
                                           {{ $trade->result === 'win' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                           {{ ucfirst($trade->result) }}
                                       </span>
                                   @endif
                               </td>
                               <td class="px-4 py-3">
                                   @if($trade->result === null)
                                       <span class="text-yellow-600">Pending</span>
                                   @else
                                       <span class="{{ $trade->result === 'win' ? 'text-green-600' : 'text-red-600' }}">
                                           {{ $trade->result === 'win' ? '+' : '-' }}${{ number_format($trade->result === 'win' ? $trade->profit : $trade->amount, 2) }}
                                       </span>
                                   @endif
                               </td>
                               <td class="px-4 py-3">
                                   @if($trade->result === null)
                                       <button onclick="openTradeModal({{ $trade->id }})" 
                                               class="p-1 text-blue-600 hover:text-blue-900 rounded-full hover:bg-blue-100">
                                           <i class="ri-edit-line text-lg"></i>
                                       </button>
                                   @endif
                               </td>
                           </tr>
                       @empty
                           <tr>
                               <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                   No trades found
                               </td>
                           </tr>
                       @endforelse
                   </tbody>
               </table>
           </div>
           
           <div class="px-4 py-3 border-t">
               {{ $trades->links() }}
           </div>
       </div>
   </div>

   <!-- Trade Update Modal -->
   <div id="tradeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
       <div class="flex items-center justify-center min-h-screen px-4">
           <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
               <div class="p-6">
                   <h3 class="text-lg font-medium text-gray-900 mb-4">Update Trade Result</h3>
                   <form id="updateTradeForm" method="POST">
                       @csrf
                       @method('PUT')
                       
                       <div class="mb-4">
                           <label class="block text-sm font-medium text-gray-700 mb-2">Result</label>
                           <select name="result" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                               <option value="win">Win</option>
                               <option value="loss">Loss</option>
                           </select>
                       </div>

                       <div class="mb-4">
                           <label class="block text-sm font-medium text-gray-700 mb-2">Profit Amount</label>
                           <input type="number" name="profit" step="0.01" min="0" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                       </div>

                       <div class="flex justify-end space-x-3">
                           <button type="button" onclick="closeTradeModal()"
                                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                               Cancel
                           </button>
                           <button type="submit"
                                   class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                               Update
                           </button>
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>

   <script>
       function openTradeModal(tradeId) {
           const modal = document.getElementById('tradeModal');
           const form = document.getElementById('updateTradeForm');
           form.action = `/admin/trades/${tradeId}`;
           modal.classList.remove('hidden');
       }

       function closeTradeModal() {
           const modal = document.getElementById('tradeModal');
           modal.classList.add('hidden');
       }
   </script>
</x-admin-layout>