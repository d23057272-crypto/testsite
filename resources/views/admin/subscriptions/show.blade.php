<x-admin-layout>
   <div class="container px-6 mx-auto">
       <div class="my-6">
           <div class="flex justify-between items-center">
               <h2 class="text-2xl font-semibold text-gray-700">Subscription Details</h2>
               <a href="{{ route('admin.subscriptions.index') }}" class="text-gray-600 hover:text-gray-900">
                   <i class="ri-arrow-left-line mr-1"></i>Back
               </a>
           </div>
       </div>

       <!-- Subscription Details -->
       <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
           <div class="bg-white rounded-lg shadow-md p-6">
               <h3 class="text-lg font-medium text-gray-900 mb-4">User Details</h3>
               <dl class="space-y-2">
                   <div>
                       <dt class="text-sm text-gray-500">Name</dt>
                       <dd class="text-sm font-medium">{{ $subscription->user->name }}</dd>
                   </div>
                   <div>
                       <dt class="text-sm text-gray-500">Email</dt>
                       <dd class="text-sm font-medium">{{ $subscription->user->email }}</dd>
                   </div>
               </dl>
           </div>

           <div class="bg-white rounded-lg shadow-md p-6">
               <h3 class="text-lg font-medium text-gray-900 mb-4">Bot Details</h3>
               <dl class="space-y-2">
                   <div>
                       <dt class="text-sm text-gray-500">Name</dt>
                       <dd class="text-sm font-medium">{{ $subscription->bot->name }}</dd>
                   </div>
                   <div>
                       <dt class="text-sm text-gray-500">Type</dt>
                       <dd class="text-sm font-medium">{{ $subscription->bot->bot_type }}</dd>
                   </div>
               </dl>
           </div>

           <div class="bg-white rounded-lg shadow-md p-6">
               <h3 class="text-lg font-medium text-gray-900 mb-4">Subscription Details</h3>
               <dl class="space-y-2">
                   <div>
                       <dt class="text-sm text-gray-500">Status</dt>
                       <dd>
                           <span class="px-2 py-1 text-xs rounded-full 
                               {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                  ($subscription->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                               {{ ucfirst($subscription->status) }}
                           </span>
                       </dd>
                   </div>
                   <div>
                       <dt class="text-sm text-gray-500">Amount</dt>
                       <dd class="text-sm font-medium">${{ number_format($subscription->amount, 2) }}</dd>
                   </div>
                   <div>
                       <dt class="text-sm text-gray-500">Total Profit</dt>
                       <dd class="text-sm font-medium text-{{ $subscription->total_profit >= 0 ? 'green' : 'red' }}-600">
                           {{ $subscription->total_profit >= 0 ? '+' : '' }}${{ number_format($subscription->total_profit, 2) }}
                       </dd>
                   </div>
                   <div>
                       <dt class="text-sm text-gray-500">Start Date</dt>
                       <dd class="text-sm font-medium">{{ $subscription->subscribed_at->format('M d, Y H:i') }}</dd>
                   </div>
                   <div>
                       <dt class="text-sm text-gray-500">End Date</dt>
                       <dd class="text-sm font-medium">{{ $subscription->expires_at ? $subscription->expires_at->format('M d, Y H:i') : 'N/A' }}</dd>
                   </div>
               </dl>
           </div>
       </div>

       <!-- Trade History -->
       <div class="bg-white rounded-lg shadow-md overflow-hidden">
           <div class="px-6 py-4 border-b border-gray-200">
               <h3 class="text-lg font-medium text-gray-900">Trade History</h3>
           </div>
           <div class="overflow-x-auto">
               <table class="w-full">
                   <thead>
                       <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                           <th class="px-4 py-3">Date</th>
                           <th class="px-4 py-3">Trading Pair</th>
                           <th class="px-4 py-3">Action</th>
                           <th class="px-4 py-3">Amount</th>
                           <th class="px-4 py-3">Result</th>
                           <th class="px-4 py-3">Profit/Loss</th>
                       </tr>
                   </thead>
                   <tbody class="bg-white divide-y">
                       @forelse($subscription->profits as $profit)
                           <tr class="text-gray-700">
                               <td class="px-4 py-3 text-sm">
                                   {{ $profit->created_at->format('M d, Y H:i') }}
                               </td>
                               <td class="px-4 py-3 text-sm">
                                   {{ $profit->trade->trading_pair }}
                               </td>
                               <td class="px-4 py-3 text-sm">
                                   <span class="px-2 py-1 text-xs rounded-full 
                                       {{ $profit->trade->action === 'buy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                       {{ ucfirst($profit->trade->action) }}
                                   </span>
                               </td>
                               <td class="px-4 py-3 text-sm">
                                   ${{ number_format($profit->amount, 2) }}
                               </td>
                               <td class="px-4 py-3 text-sm">
                                   <span class="px-2 py-1 text-xs rounded-full 
                                       {{ $profit->trade->result === 'win' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                       {{ ucfirst($profit->trade->result) }}
                                   </span>
                               </td>
                               <td class="px-4 py-3 text-sm">
                                   <span class="{{ $profit->trade->result === 'win' ? 'text-green-600' : 'text-red-600' }}">
                                       {{ $profit->trade->result === 'win' ? '+' : '-' }}${{ number_format($profit->trade->result === 'win' ? $profit->trade->profit : $profit->trade->amount, 2) }}
                                   </span>
                               </td>
                           </tr>
                       @empty
                           <tr>
                               <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                   No trades found
                               </td>
                           </tr>
                       @endforelse
                   </tbody>
               </table>
           </div>
       </div>
   </div>
</x-admin-layout>