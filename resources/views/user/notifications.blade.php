<x-user-layout :title="'Notifications'">
    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ url()->previous() }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">Notifications</h1>
            <button id="mark-all-read" class="text-sm text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 transition-colors">
                Mark All Read
            </button>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3" id="notifications-container">
            @forelse($notifications as $notification)
                @php
                    $msg = $notification->message;

                    // Step 1: Remove all wallet addresses
                    $msg = preg_replace([
                        '/0x[a-fA-F0-9]{4,}\.\.\.[a-fA-F0-9]{2,}/i',   // truncated: 0xAbC...123
                        '/\b0x[a-fA-F0-9]{20,}\b/i',                   // full Ethereum address
                        '/\b(?:bc1|[13])[A-HJ-NP-Za-km-z1-9]{20,}\b/', // Bitcoin (optional)
                    ], '', $msg);

                    // Step 2: Fix "to [nothing] has been" → "to external address has been"
                    $msg = preg_replace(
                        '/\bto\s+(?=has been\b)/i',
                        'to external address ',
                        $msg
                    );

                    // Step 3: Clean extra whitespace
                    $msg = preg_replace('/\s+/', ' ', trim($msg));
                @endphp

                <div class="notification-item bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700" data-id="{{ $notification->id }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3">
                            <div class="mt-1 w-10 h-10 rounded-full {{ $notification->is_read ? 'bg-gray-100 dark:bg-gray-700' : 'bg-blue-50 dark:bg-blue-900/30' }} flex items-center justify-center">
                                <i class="fas fa-bell {{ $notification->is_read ? 'text-gray-400 dark:text-gray-500' : 'text-blue-500 dark:text-blue-400' }} text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h2 class="font-medium text-gray-900 dark:text-white mb-1">{{ $notification->title }}</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $msg }}
                                </p>
                                <span class="text-xs text-gray-500 dark:text-gray-500 mt-1 block">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-2">
                            @if(!$notification->is_read)
                                <button class="mark-read w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-500 dark:hover:text-blue-400 transition-colors" data-id="{{ $notification->id }}">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            @endif
                            <button class="delete-notification w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-500 dark:hover:text-red-400 transition-colors" data-id="{{ $notification->id }}">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-gray-400 dark:text-gray-500 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">No notifications yet</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ripple effect for touch devices
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('touchstart', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.touches[0].clientX - rect.left;
                const y = e.touches[0].clientY - rect.top;
                const ripple = document.createElement('div');
                ripple.classList.add('ripple');
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
        
        // Mark single notification as read
        document.querySelectorAll('.mark-read').forEach(button => {
            button.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                const item = this.closest('.notification-item');
                const bellIcon = item.querySelector('.fas.fa-bell');
                const iconContainer = item.querySelector('.mt-1.w-10.h-10.rounded-full');
                
                // Update UI immediately
                bellIcon.className = 'fas fa-bell text-gray-400 dark:text-gray-500 text-lg';
                iconContainer.className = 'mt-1 w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center';
                this.remove();
                
                // Update server
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                }).catch(err => console.error('Failed to mark as read:', err));
            });
        });

        // Delete notification
        document.querySelectorAll('.delete-notification').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const item = this.closest('.notification-item');
                item.style.opacity = '0';
                item.style.transform = 'translateX(10px)';
                setTimeout(() => {
                    fetch(`/notifications/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) item.remove();
                        else {
                            // Revert animation on failure
                            item.style.opacity = '1';
                            item.style.transform = 'translateX(0)';
                        }
                    })
                    .catch(err => {
                        console.error('Failed to delete notification:', err);
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    });
                }, 300);
            });
        });

        // Mark all notifications as read
        document.getElementById('mark-all-read')?.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => this.style.transform = 'scale(1)', 150);
            
            // Update all notification items immediately
            document.querySelectorAll('.notification-item').forEach(item => {
                const bellIcon = item.querySelector('.fas.fa-bell');
                const iconContainer = item.querySelector('.mt-1.w-10.h-10.rounded-full');
                
                if (bellIcon && bellIcon.classList.contains('text-blue-500')) {
                    bellIcon.className = 'fas fa-bell text-gray-400 dark:text-gray-500 text-lg';
                    iconContainer.className = 'mt-1 w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center';
                    item.querySelector('.mark-read')?.remove();
                }
            });
            
            // Update server
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).catch(err => console.error('Failed to mark all as read:', err));
        });
    });
    </script>
    <style>
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.05);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    /* Dark mode pagination support */
    .pagination {
        @apply dark:bg-gray-800 dark:border-gray-700;
    }
    .pagination a,
    .pagination .active span {
        @apply dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600;
    }
    .pagination .disabled span {
        @apply dark:text-gray-500;
    }
    </style>
    @endpush
</x-user-layout>