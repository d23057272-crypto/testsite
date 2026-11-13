<x-user-layout :title="'Referrals'">
    <div id="toast" class="fixed top-4 right-4 z-50 bg-blue-500 dark:bg-blue-600 text-white px-6 py-3 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out hidden opacity-90">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            Link copied to clipboard!
        </div>
    </div>
    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('settings') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">Referrals</h1>
            <div class="w-8"></div>
        </div>

        <!-- Referral Stats Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-users text-blue-500 dark:text-blue-400"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Total Referrals</p>
                    <p class="text-2xl font-medium text-gray-900 dark:text-white">{{ $referral_count }}</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-calendar-alt text-blue-500 dark:text-blue-400"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">This Month</p>
                    <p class="text-2xl font-medium text-gray-900 dark:text-white">
                        {{ $referrals->whereBetween('created_at', [now()->startOfMonth(), now()])->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Referral Link Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Your Referral Link</h2>
            <div class="relative">
                <input 
                    type="text" 
                    value="{{ $referral_link }}" 
                    class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 pr-12 text-sm text-gray-900 dark:text-white"
                    readonly
                >
                <button 
                    id="copy-referral-button"
                    onclick="copyToClipboard('{{ $referral_link }}')"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-500 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
                >
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">
                Share this link with friends to earn rewards
            </p>
        </div>

        <!-- Share Button -->
        <div class="mb-6">
            <button onclick="shareReferralLink()" class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-xl transition-colors flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-900">
                <i class="fas fa-share-alt mr-2"></i>
                Share Your Referral Link
            </button>
        </div>

        <!-- Recent Referrals -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Referrals</h2>
            @if($referrals->count() > 0)
                <div class="space-y-4">
                    @foreach($referrals as $referral)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-500 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $referral->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Joined {{ $referral->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">No referrals yet</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Share your referral link to get started</p>
                </div>
            @endif
        </div>
    </main>

    <style>
    /* Copy success state with dark mode support */
    .copy-success {
        @apply bg-green-100 text-green-500;
    }
    .dark .copy-success {
        @apply bg-green-900/20 text-green-400;
    }
    </style>

    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById('toast');
            const copyButton = document.getElementById('copy-referral-button');
            
            // Show toast
            toast.classList.remove('hidden', 'translate-x-full');
            
            // Update copy button to success state
            copyButton.innerHTML = '<i class="fas fa-check"></i>';
            copyButton.classList.add('copy-success');
            copyButton.classList.remove('bg-blue-50', 'dark:bg-blue-900/30', 'text-blue-500', 'dark:text-blue-400', 'hover:bg-blue-100', 'dark:hover:bg-blue-900/50');
            
            // Revert button after 2 seconds
            setTimeout(() => {
                copyButton.innerHTML = '<i class="fas fa-copy"></i>';
                copyButton.classList.remove('copy-success');
                copyButton.classList.add('bg-blue-50', 'text-blue-500', 'hover:bg-blue-100');
                if (document.documentElement.classList.contains('dark')) {
                    copyButton.classList.add('dark:bg-blue-900/30', 'dark:text-blue-400', 'dark:hover:bg-blue-900/50');
                }
            }, 2000);
            
            // Hide toast after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 300);
            }, 3000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
            alert('Failed to copy. Please select and copy manually.');
        });
    }
    
    function shareReferralLink() {
        const referralLink = "{{ $referral_link }}";
        
        // Check if Web Share API is supported
        if (navigator.share) {
            navigator.share({
                title: 'Join with my referral link',
                text: 'Use my referral link to join:',
                url: referralLink
            })
            .catch(err => {
                console.error('Share failed:', err);
                // Fallback to copy if sharing fails
                copyToClipboard(referralLink);
            });
        } else {
            // Fallback for browsers that don't support Web Share API
            copyToClipboard(referralLink);
        }
    }
    </script>
</x-user-layout>