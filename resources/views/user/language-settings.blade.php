<x-user-layout :title="'Language'">
    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ url()->previous() }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">{{ __('Language') }}</h1>
            <div class="w-8"></div>
        </div>

        <form method="POST" action="{{ route('language.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="languageSearch" class="sr-only">{{ __('Search') }}</label>
                <input
                    id="languageSearch"
                    type="search"
                    placeholder="{{ __('Search') }}"
                    autocomplete="off"
                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <div class="rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700">
                @foreach($availableLocales as $code => $name)
                    <label
                        data-language-option
                        data-search="{{ strtolower($name . ' ' . $code) }}"
                        class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-800 border-b border-gray-100 dark:border-gray-700 last:border-b-0 cursor-pointer transition-colors"
                    >
                        <span class="font-medium text-gray-900 dark:text-white">{{ $name }}</span>
                        <input type="radio" name="locale" value="{{ $code }}"
                            {{ (Auth::user()->locale ?? config('app.locale')) === $code ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-offset-0 dark:focus:ring-offset-gray-900 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                    </label>
                @endforeach
                <p id="noLanguagesFound" class="hidden p-4 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('No languages match your search.') }}
                </p>
            </div>

            <button type="submit" class="w-full mt-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-900">
                {{ __('Save') }}
            </button>
        </form>
    </main>

    @push('scripts')
        <script>
            const languageSearch = document.getElementById('languageSearch');
            const languageOptions = Array.from(document.querySelectorAll('[data-language-option]'));
            const noLanguagesFound = document.getElementById('noLanguagesFound');

            languageSearch.addEventListener('input', function () {
                const query = this.value.trim().toLowerCase();
                let visibleCount = 0;

                languageOptions.forEach(function (option) {
                    const isVisible = option.dataset.search.includes(query);
                    option.classList.toggle('hidden', !isVisible);
                    visibleCount += isVisible ? 1 : 0;
                });

                noLanguagesFound.classList.toggle('hidden', visibleCount > 0);
            });
        </script>
    @endpush
</x-user-layout>