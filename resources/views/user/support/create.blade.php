<x-user-layout :title="'Create Support Ticket'">
    <main class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('support') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">Create Ticket</h1>
            <div class="w-8"></div>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden">
            <div class="px-5 py-4 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ticket Details</h2>
            </div>

            <form action="{{ route('support.store') }}" method="POST" class="p-5 space-y-5">
                @csrf

                <!-- Subject Field -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="subject" 
                           id="subject" 
                           required 
                           maxlength="255"
                           value="{{ old('subject') }}"
                           class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="e.g., Unable to access my account">
                    @error('subject')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="low" {{ old('priority', 'medium') == 'low' ? 'checked' : '' }} class="peer sr-only">
                            <div class="text-center py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 peer-checked:text-blue-600 dark:peer-checked:text-blue-400 transition-all">
                                Low
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="medium" {{ old('priority', 'medium') == 'medium' ? 'checked' : '' }} class="peer sr-only">
                            <div class="text-center py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 peer-checked:text-blue-600 dark:peer-checked:text-blue-400 transition-all">
                                Medium
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="high" {{ old('priority') == 'high' ? 'checked' : '' }} class="peer sr-only">
                            <div class="text-center py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 peer-checked:text-red-600 dark:peer-checked:text-red-400 transition-all">
                                High
                            </div>
                        </label>
                    </div>
                    @error('priority')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message Field -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" 
                              id="message" 
                              required 
                              rows="5" 
                              minlength="10" 
                              maxlength="5000"
                              class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                              placeholder="Please describe your issue in detail...">{{ old('message') }}</textarea>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Min 10 characters</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Max 5000</p>
                    </div>
                    @error('message')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    <i class="fas fa-paper-plane mr-2"></i> Create Ticket
                </button>
            </form>
        </div>

        <!-- Help Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Need immediate help? Visit our 
                <a href="{{ route('help.center') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Help Center</a>
            </p>
        </div>
    </main>
</x-user-layout>