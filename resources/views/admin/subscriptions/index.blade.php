<x-admin-layout>
   <div class="container px-6 mx-auto">
       <div class="flex justify-between items-center my-6">
           <h2 class="text-2xl font-semibold text-gray-700">Bot Subscriptions</h2>
       </div>

       <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />

       <!-- Stats Cards -->
       <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
           <div class="bg-white rounded-lg shadow-md p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                       <i class="ri-funds-box-line text-2xl text-blue-500"></i>
                   </div>
                   <div class="ml-4">
                       <p class="text-gray-500 text-sm">Total Subscriptions</p>
                       <p class="text-xl font-semibold">{{ number_format($subscriptions->total()) }}</p>
                   </div>
               </div>
           </div>
           
           <div class="bg-white rounded-lg shadow-md p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                       <i class="ri-coins-line text-2xl text-green-500"></i>
                   </div>
                   <div class="ml-4">
                       <p class="text-gray-500 text-sm">Total Investment</p>
                       <p class="text-xl font-semibold">${{ number_format($subscriptions->sum('amount'), 2) }}</p>
                   </div>
               </div>
           </div>
           
           <div class="bg-white rounded-lg shadow-md p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                       <i class="ri-bar-chart-line text-2xl text-purple-500"></i>
                   </div>
                   <div class="ml-4">
                       <p class="text-gray-500 text-sm">Total Profit</p>
                       <p class="text-xl font-semibold">${{ number_format($subscriptions->sum('total_profit'), 2) }}</p>
                   </div>
               </div>
           </div>
           
           <div class="bg-white rounded-lg shadow-md p-6">
               <div class="flex items-center">
                   <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                       <i class="ri-user-follow-line text-2xl text-yellow-500"></i>
                   </div>
                   <div class="ml-4">
                       <p class="text-gray-500 text-sm">Active Subscriptions</p>
                       <p class="text-xl font-semibold">{{ $subscriptions->where('status', 'active')->count() }}</p>
                   </div>
               </div>
           </div>
       </div>

       <!-- Subscriptions Table -->
       <div class="bg-white rounded-lg shadow-md overflow-hidden">
           <div class="overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                   <thead>
                       <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                           <th class="px-4 py-3">User/Bot</th>
                           <th class="px-4 py-3">Amount</th>
                           <th class="px-4 py-3">Profit</th>
                           <th class="px-4 py-3">Status</th>
                           <th class="px-4 py-3">Duration</th>
                           <th class="px-4 py-3">Actions</th>
                       </tr>
                   </thead>
                   <tbody class="bg-white divide-y">
                       @forelse($subscriptions as $subscription)
                           <tr class="text-gray-700">
                               <td class="px-4 py-3">
                                   <div class="flex flex-col">
                                       <span class="font-medium">{{ $subscription->user->name }}</span>
                                       <span class="text-sm text-gray-500">{{ $subscription->bot->name }}</span>
                                   </div>
                               </td>
                               <td class="px-4 py-3">${{ number_format($subscription->amount, 2) }}</td>
                               <td class="px-4 py-3">
                                   <span class="text-{{ $subscription->total_profit >= 0 ? 'green' : 'red' }}-600">
                                       {{ $subscription->total_profit >= 0 ? '+' : '' }}${{ number_format($subscription->total_profit, 2) }}
                                   </span>
                               </td>
                               <td class="px-4 py-3">
                                   <span class="px-2 py-1 text-xs rounded-full 
                                       {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                          ($subscription->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                       {{ ucfirst($subscription->status) }}
                                   </span>
                               </td>
                               <td class="px-4 py-3 text-sm">
                                   <div class="flex flex-col">
                                       <span>Start: {{ $subscription->subscribed_at->format('M d, Y H:i') }}</span>
                                       <span>End: {{ $subscription->expires_at ? $subscription->expires_at->format('M d, Y H:i') : 'N/A' }}</span>
                                   </div>
                               </td>
                               <td class="px-4 py-3">
                                   <div class="flex items-center space-x-2">
                                       <a href="{{ route('admin.subscriptions.show', $subscription) }}" 
                                          class="p-1 text-blue-600 hover:text-blue-900 rounded-full hover:bg-blue-100">
                                           <i class="ri-eye-line text-lg"></i>
                                       </a>
                                   </div>
                               </td>
                           </tr>
                       @empty
                           <tr>
                               <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                   No subscriptions found
                               </td>
                           </tr>
                       @endforelse
                   </tbody>
               </table>
           </div>
           
           <div class="px-4 py-3 border-t">
               {{ $subscriptions->links() }}
           </div>
       </div>
   </div>
</x-admin-layout>