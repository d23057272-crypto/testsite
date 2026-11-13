<x-admin-layout>
    <div class="container px-6 mx-auto py-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Dashboard</a>
            <i class="ri-arrow-right-s-line mx-2"></i>
            <a href="{{ route('admin.support.index') }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Support</a>
            <i class="ri-arrow-right-s-line mx-2"></i>
            <span class="text-gray-700 dark:text-gray-300 font-medium">User Tickets</span>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $title ?? 'User Tickets' }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Viewing all support tickets for this user</p>
        </div>

        <!-- User Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h3>
                        <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1"><i class="ri-mail-line"></i> {{ $user->email }}</span>
                            <span class="flex items-center gap-1"><i class="ri-phone-line"></i> {{ $user->phone ?? 'No phone' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('viewuser', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="ri-user-line mr-2"></i> View Profile
                        </a>
                        <a href="{{ route('admin.support.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            <i class="ri-arrow-left-line mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 dark:text-white">All Tickets ({{ $tickets->total() }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ticket</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Reply</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">#{{ $ticket->ticket_id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($ticket->subject, 40) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($ticket->status == 'open')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Open
                                        </span>
                                    @elseif($ticket->status == 'answered')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Answered
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Closed
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
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $ticket->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.support.show', $ticket) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-purple-100 dark:hover:bg-purple-900/30 hover:text-purple-600 dark:hover:text-purple-400 transition-all" title="View Ticket">
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
                                        <p class="text-sm text-gray-500 dark:text-gray-400">This user has not created any support tickets yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tickets->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>