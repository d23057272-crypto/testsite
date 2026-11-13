<x-user-layout :title="'Backup Phrase'">
    <main class="w-full mx-auto p-4 pb-24 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('settings') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">Backup Phrase</h1>
            <div class="w-8"></div>
        </div>

        <!-- Warning Screen (Default View) -->
        <div id="warning-screen" class="text-center py-8">
            <div class="inline-block p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-full mb-6">
                <i class="fas fa-lock text-yellow-600 dark:text-yellow-400 text-3xl"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Reveal Your Backup Phrase</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                This phrase gives full access to your wallet. Never share it with anyone.
                Make sure you're in a safe, private place before viewing it.
            </p>
            <button id="show-phrase-btn" class="w-full max-w-xs mx-auto py-3 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white rounded-xl font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-red-400 dark:focus:ring-offset-gray-900">
                I Understand — Show Phrase
            </button>
        </div>

        <!-- Phrase Screen (Hidden by Default) -->
        <div id="phrase-screen" class="hidden">
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-700 p-4 mb-6 rounded-r">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-red-500 dark:text-red-400 mt-0.5 mr-2"></i>
                    <p class="text-sm text-red-700 dark:text-red-300">
                        <strong>Never share this phrase.</strong> It gives full access to your wallet.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6">
                @foreach(explode(' ', $phrase) as $index => $word)
                    <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-lg px-3 py-2 border border-gray-200 dark:border-gray-700">
                        <span class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs flex items-center justify-center mr-2 font-medium">
                            {{ $index + 1 }}
                        </span>
                        <span class="font-mono text-sm text-gray-900 dark:text-white">{{ $word }}</span>
                    </div>
                @endforeach
            </div>

            <button id="copy-btn" class="w-full py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white rounded-xl font-medium mb-3 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-900">
                Copy Backup Phrase
            </button>
            <a href="{{ route('settings') }}" class="block w-full py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-xl text-center font-medium transition-colors">
                I Have Saved It
            </a>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const warningScreen = document.getElementById('warning-screen');
            const phraseScreen = document.getElementById('phrase-screen');
            const showPhraseBtn = document.getElementById('show-phrase-btn');
            const copyBtn = document.getElementById('copy-btn');

            showPhraseBtn?.addEventListener('click', () => {
                warningScreen.classList.add('hidden');
                phraseScreen.classList.remove('hidden');
                
                // Add security animation: fade in with slight scale
                phraseScreen.style.opacity = '0';
                phraseScreen.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    phraseScreen.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    phraseScreen.style.opacity = '1';
                    phraseScreen.style.transform = 'scale(1)';
                }, 10);
            });

            copyBtn?.addEventListener('click', () => {
                const phrase = `{{ $phrase }}`;
                const originalText = copyBtn.textContent;
                
                navigator.clipboard.writeText(phrase).then(() => {
                    // Visual feedback
                    copyBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                    copyBtn.classList.add('bg-green-600', 'dark:bg-green-700');
                    copyBtn.classList.remove('bg-blue-600', 'dark:bg-blue-700');
                    
                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                        copyBtn.classList.remove('bg-green-600', 'dark:bg-green-700');
                        copyBtn.classList.add('bg-blue-600', 'dark:bg-blue-700');
                    }, 2000);
                }).catch(() => {
                    alert('Failed to copy. Please select and copy manually.');
                });
            });
        });
    </script>
</x-user-layout>