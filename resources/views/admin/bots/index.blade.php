<x-admin-layout>
   <div class="container px-6 mx-auto">
       <div class="flex justify-between items-center my-6">
           <h2 class="text-2xl font-semibold text-gray-700">Trading Bots</h2>
           <a href="{{ route('admin.bots.create') }}" 
              class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
               <i class="ri-add-line mr-2"></i>Create Bot
           </a>
       </div>

       {{-- Flash Messages --}}
        <x-session-status class="mb-4" :status="session('status')" :errors="$errors" />

       <div class="bg-white rounded-lg shadow-md overflow-hidden">
           <div class="overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                   <thead>
                       <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                           <th class="px-4 py-3">Bot</th>
                           <th class="px-4 py-3">Type</th>
        				   <th class="px-4 py-3">Status</th>
                           <th class="px-4 py-3">Subscriptions</th>
                           <th class="px-4 py-3">Investment</th>
                           <th class="px-4 py-3">Profit</th>
                           <th class="px-4 py-3">Actions</th>
                       </tr>
                   </thead>
                   <tbody class="bg-white divide-y">
                       @forelse($bots as $bot)
                           <tr class="text-gray-700">
                               <td class="px-4 py-3">
                                   <div class="flex items-center">
                                       <div class="flex-shrink-0 h-10 w-10">
                                           <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                               <i class="ri-robot-line text-xl text-purple-600"></i>
                                           </div>
                                       </div>
                                       <div class="ml-4">
                                           <div class="text-sm font-medium">{{ $bot->name }}</div>
                                           <div class="text-xs text-gray-400">Created {{ $bot->created_at->format('M d, Y') }}</div>
                                       </div>
                                   </div>
                               </td>
                               <td class="px-4 py-3 text-sm">
                                   <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                       {{ $bot->bot_type }}
                                   </span>
                               </td>
                                  <td class="px-4 py-3 text-sm">
    @php $isActive = $bot->settings['is_active'] ?? false; @endphp
    <span class="px-2 py-1 text-xs rounded-full {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
        {{ $isActive ? 'Active' : 'Inactive' }}
    </span>
</td>
                               <td class="px-4 py-3">
                                   <div class="text-sm">{{ $bot->subscriptions_count ?? 0 }} Total</div>
                                   <div class="text-xs text-gray-500">
                                       {{ $bot->active_subscriptions_count ?? 0 }} Active
                                   </div>
                               </td>
                               <td class="px-4 py-3">
                                   <div class="text-sm">${{ number_format($bot->subscriptions_sum_amount ?? 0, 2) }}</div>
                               </td>
                               <td class="px-4 py-3">
                                   <div class="text-sm">${{ number_format($bot->subscriptions_sum_total_profit ?? 0, 2) }}</div>
                               </td>
                               <td class="px-4 py-3">
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.bots.show', $bot) }}" 
           class="p-1 text-blue-600 hover:text-blue-900 rounded-full hover:bg-blue-100">
           <i class="ri-eye-line text-lg"></i>
        </a>
        <a href="{{ route('admin.bots.edit', $bot) }}"
           class="p-1 text-yellow-600 hover:text-yellow-900 rounded-full hover:bg-yellow-100">
           <i class="ri-edit-line text-lg"></i>
        </a>
        <form action="{{ route('admin.bots.toggle-status', $bot) }}" method="POST" 
              class="inline">
            @csrf
            @method('PATCH')
            @php $isActive = $bot->settings['is_active'] ?? false; @endphp
            <button type="submit" 
                    class="p-1 {{ $isActive ? 'text-red-600 hover:text-red-900 hover:bg-red-100' : 'text-green-600 hover:text-green-900 hover:bg-green-100' }} rounded-full">
                <i class="{{ $isActive ? 'ri-stop-circle-line' : 'ri-play-circle-line' }} text-lg"></i>
            </button>
        </form>
        <form action="{{ route('admin.bots.destroy', $bot) }}" method="POST" 
              class="inline" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="p-1 text-red-600 hover:text-red-900 rounded-full hover:bg-red-100">
                <i class="ri-delete-bin-line text-lg"></i>
            </button>
        </form>
    </div>
</td>
                           </tr>
                       @empty
                           <tr>
                               <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                   No bots found
                               </td>
                           </tr>
                       @endforelse
                   </tbody>
               </table>
           </div>
           
           <div class="px-4 py-3 border-t">
               {{ $bots->links() }}
           </div>
       </div>
   </div>
</x-admin-layout>