<x-user-layout :title="'Support Tickets'">
    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">Support Tickets</h1>
            <div class="w-8"></div>
        </div>

        <!-- New Ticket Button -->
        <div class="flex justify-end mb-6">
            <a href="{{ route('support.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors shadow-sm">
                <i class="fas fa-plus mr-1"></i> New Ticket
            </a>
        </div>

        <!-- Tickets List -->
        @if(count($tickets) > 0)
            <div class="space-y-4">
                @foreach($tickets as $ticket)
                    <a href="{{ route('support.show', $ticket['id']) }}" class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:border-blue-500/50 hover:shadow-md transition-all duration-200 group">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-mono text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md">{{ $ticket['id'] }}</span>
                                    @php
                                        $statusColors = [
                                            'open' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            'answered' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'closed' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full {{ $statusColors[$ticket['status']] ?? $statusColors['closed'] }}">
                                        {{ ucfirst($ticket['status']) }}
                                    </span>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                    {{ $ticket['subject'] }}
                                </h3>
                                @if($ticket['last_sender'])
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">
                                        <span class="font-medium">{{ $ticket['last_sender'] }}:</span> {{ $ticket['last_message'] }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket['updated_at']->diffForHumans() }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $ticket['message_count'] }} {{ $ticket['message_count'] > 1 ? 'messages' : 'message' }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <i class="fas fa-inbox text-2xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No tickets yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4 text-sm">Create a new ticket to get help from our support team</p>
                <a href="{{ route('support.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Create Ticket
                </a>
            </div>
        @endif
    </main>
</x-user-layout>