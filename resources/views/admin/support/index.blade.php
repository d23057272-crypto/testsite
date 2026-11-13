<x-admin-layout>
    <div class="container px-6 mx-auto py-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Support Tickets</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage and respond to user support requests</p>
            </div>
            <a href="{{ route('admin.support.create') }}"
               class="inline-flex items-center px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <i class="ri-add-line mr-2 text-lg"></i>
                Open Ticket
            </a>
        </div>

        <!-- Status Filter Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="flex flex-wrap items-center gap-1 p-2">
                <a href="{{ route('admin.support.index') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                   {{ !isset($filter) 
                      ? 'bg-purple-600 text-white shadow-sm' 
                      : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    All Tickets
                </a>
                <a href="{{ route('admin.support.open') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                   {{ isset($filter) && $filter == 'open' 
                      ? 'bg-amber-500 text-white shadow-sm' 
                      : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ isset($filter) && $filter == 'open' ? 'bg-white' : 'bg-amber-500' }}"></span>
                        Open
                    </span>
                </a>
                <a href="{{ route('admin.support.answered') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                   {{ isset($filter) && $filter == 'answered' 
                      ? 'bg-emerald-500 text-white shadow-sm' 
                      : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ isset($filter) && $filter == 'answered' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                        Answered
                    </span>
                </a>
                <a href="{{ route('admin.support.closed') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                   {{ isset($filter) && $filter == 'closed' 
                      ? 'bg-gray-600 text-white shadow-sm' 
                      : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ isset($filter) && $filter == 'closed' ? 'bg-white' : 'bg-gray-500' }}"></span>
                        Closed
                    </span>
                </a>
            </div>
        </div>

        <!-- Tickets Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ticket</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Reply</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-ticket-line text-purple-600 dark:text-purple-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">#{{ $ticket->ticket_id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($ticket->user)
                                        <a href="{{ route('admin.support.user', $ticket->user_id) }}" class="flex items-center gap-3 group">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                                {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ $ticket->user->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->user->email }}</div>
                                            </div>
                                        </a>
                                    @else
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm font-bold flex-shrink-0">?</div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Deleted User</div>
                                                <div class="text-xs text-gray-400 dark:text-gray-500">ID: {{ $ticket->user_id }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($ticket->subject, 45) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($ticket->status == 'open')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Open
                                        </span>
                                    @elseif($ticket->status == 'answered')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Answered
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Closed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if(($ticket->priority ?? 'normal') == 'high')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                            <i class="ri-fire-line mr-1"></i>High
                                        </span>
                                    @elseif(($ticket->priority ?? 'normal') == 'medium')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                            <i class="ri-arrow-up-line mr-1"></i>Medium
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                            Low
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                                        <i class="ri-time-line text-gray-400"></i>
                                        {{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : 'No replies' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.support.show', $ticket) }}" 
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-purple-100 dark:hover:bg-purple-900/30 hover:text-purple-600 dark:hover:text-purple-400 transition-all"
                                       title="View Ticket">
                                        <i class="ri-eye-line text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                                            <i class="ri-inbox-2-line text-4xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">No tickets found</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">There are no support tickets matching your current filter. Try a different status or create a new ticket.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($tickets->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>