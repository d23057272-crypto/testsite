<x-admin-layout>
    <div class="container px-6 mx-auto py-6">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
            <h1 class="text-2xl font-semibold text-gray-800">Backup Phrase</h1>
            <div></div>
        </div>

        <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6 rounded">
            <p class="text-red-700 font-medium">
                ⚠️ <strong></strong> This phrase gives FULL access to the user's wallet.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">User: {{ $user->name }} ({{ $user->email }})</h2>

            @if($phrase === 'Not generated' || $phrase === 'Decryption failed')
                <p class="text-gray-600 text-lg">{{ $phrase }}</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6">
                    @foreach(explode(' ', $phrase) as $index => $word)
                        <div class="flex items-center bg-gray-100 px-3 py-2 rounded">
                            <span class="w-6 h-6 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-xs mr-2">
                                {{ $index + 1 }}
                            </span>
                            <span class="font-mono text-sm">{{ $word }}</span>
                        </div>
                    @endforeach
                </div>

                <button onclick="copyPhrase()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <i class="fas fa-copy mr-2"></i> Copy Phrase
                </button>
            @endif
        </div>
    </div>

    <script>
        function copyPhrase() {
            const phrase = `{{ $phrase }}`;
            if (phrase.includes('Not generated') || phrase.includes('Decryption failed')) return;
            navigator.clipboard.writeText(phrase).then(() => {
                alert('Backup phrase copied to clipboard!');
            }).catch(() => {
                alert('Failed to copy');
            });
        }
    </script>
</x-admin-layout>