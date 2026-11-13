@props(['status', 'errors'])

@if ($status || $errors->any())
    <div {{ $attributes->merge(['class' => 'flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md']) }}>
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ $status }}</span>
            @foreach ($errors->all() as $error)
                <p class="mt-2 text-red-100">{{ $error }}</p>
            @endforeach
        </div>
        <button onclick="this.parentElement.remove()" class="text-purple-100 hover:text-white transition-colors duration-150">
            <span>Dismiss →</span>
        </button>
    </div>
@endif
