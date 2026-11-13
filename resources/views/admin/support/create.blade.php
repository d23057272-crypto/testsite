<x-admin-layout>
    <div class="container px-6 mx-auto py-6">
        <!-- Page Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Open Support Ticket</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create a new support ticket on behalf of a user</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ticket Details</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.support.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- User Select -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Select User <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="user_id" 
                                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent appearance-none cursor-pointer"
                                            required>
                                        <option value="">Choose a user...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="ri-arrow-down-s-line text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="subject"
                                       placeholder="Brief description of the issue..."
                                       class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                       required>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Priority
                                </label>
                                <div class="relative">
                                    <select name="priority"
                                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent appearance-none cursor-pointer">
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="ri-arrow-down-s-line text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Message -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Message <span class="text-red-500">*</span>
                                </label>
                                <textarea name="message"
                                          rows="6"
                                          placeholder="Describe the issue in detail..."
                                          class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                                          required></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center gap-3 pt-2">
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                    <i class="ri-ticket-line mr-2"></i>
                                    Open Ticket
                                </button>
                                <a href="{{ route('admin.support.index') }}"
                                   class="inline-flex items-center px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mb-4">
                        <i class="ri-lightbulb-line text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Creating Tickets</h3>
                    <p class="text-sm text-purple-100 leading-relaxed mb-4">
                        When you create a ticket on behalf of a user, they will receive an email notification. The ticket status will be set to "Answered" since you're the first to respond.
                    </p>
                    <div class="space-y-3 text-sm text-purple-100">
                        <div class="flex items-start gap-2">
                            <i class="ri-check-line mt-0.5 text-white"></i>
                            <span>User gets email notification</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="ri-check-line mt-0.5 text-white"></i>
                            <span>Discord alert sent to admins</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="ri-check-line mt-0.5 text-white"></i>
                            <span>Appears in admin dashboard</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>