<x-admin-layout>
    <div class="min-h-screen bg-gray-50">

        <!-- Hero Header -->
        <div class="relative overflow-hidden" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 50%, #c084fc 100%);">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/3 translate-y-1/3"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-white/80 text-sm font-medium tracking-wider uppercase">Email Campaign</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white">Send Email</h1>
                        <p class="text-white/70 mt-2 max-w-xl">Craft beautiful emails and reach your audience with personalized campaigns.</p>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="text-center md:text-right">
                            <div class="text-white/60 text-sm">Total Users</div>
                            <div class="text-white text-3xl font-bold">{{ number_format($users->count()) }}</div>
                        </div>
                        <div class="w-px h-12 bg-white/20 hidden md:block"></div>
                        <div class="text-center md:text-right hidden md:block">
                            <div class="text-white/60 text-sm">Active</div>
                            <div class="text-white text-3xl font-bold">{{ number_format($users->where('email_verified_at', '!=', null)->count()) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10 pb-12">

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 flex items-start shadow-lg animate-fade-in">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 flex items-start shadow-lg animate-fade-in">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <form id="newsletterForm" action="{{ route('admin.newsletter.send') }}" method="POST">
                        @csrf

                        <!-- Subject Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                <h3 class="font-semibold text-gray-800">Email Subject</h3>
                            </div>
                            <div class="p-6">
                                <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required
                                    placeholder="Enter an engaging subject line..."
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300">
                                <x-input-error :messages="$errors->get('subject')" class="mt-2 text-sm" />
                                <p class="mt-2 text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path></svg>
                                    Tip: Keep it under 50 characters for best open rates
                                </p>
                            </div>
                        </div>

                        <!-- Template Selection -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <h3 class="font-semibold text-gray-800">Choose Template</h3>
                            </div>
                            <div class="p-6">
                                <input type="hidden" name="template" id="selectedTemplate" value="standard">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                                    <!-- Standard -->
                                    <div class="template-card selected bg-white border-2 border-purple-300 rounded-xl overflow-hidden cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-lg" data-template="standard" onclick="selectTemplate(this, 'standard')">
                                        <div class="h-24 bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex items-center justify-between mb-1">
                                                <h4 class="font-semibold text-gray-800">Standard</h4>
                                                <div class="check-icon w-5 h-5 bg-purple-600 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500">Clean, professional layout for general updates</p>
                                        </div>
                                    </div>

                                    <!-- Announcement -->
                                    <div class="template-card bg-white border-2 border-gray-200 rounded-xl overflow-hidden cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-lg" data-template="announcement" onclick="selectTemplate(this, 'announcement')">
                                        <div class="h-24 bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex items-center justify-between mb-1">
                                                <h4 class="font-semibold text-gray-800">Announcement</h4>
                                                <div class="check-icon w-5 h-5 bg-gray-300 rounded-full flex items-center justify-center hidden">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500">Bold design for important news and alerts</p>
                                        </div>
                                    </div>

                                    <!-- Promotion -->
                                    <div class="template-card bg-white border-2 border-gray-200 rounded-xl overflow-hidden cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-lg" data-template="promotion" onclick="selectTemplate(this, 'promotion')">
                                        <div class="h-24 bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex items-center justify-between mb-1">
                                                <h4 class="font-semibold text-gray-800">Promotion</h4>
                                                <div class="check-icon w-5 h-5 bg-gray-300 rounded-full flex items-center justify-center hidden">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500">Vibrant layout for offers and referrals</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Editor -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <h3 class="font-semibold text-gray-800">Email Content</h3>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <span>Rich text editor enabled</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <textarea id="content" name="content" class="w-full">{{ old('content') }}</textarea>
                                <x-input-error :messages="$errors->get('content')" class="mt-2 text-sm" />
                            </div>
                        </div>

                        <!-- Recipients -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <h3 class="font-semibold text-gray-800">Select Recipients</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                    <label class="recipient-option relative flex items-center p-4 border-2 border-purple-300 bg-purple-50/30 rounded-xl cursor-pointer transition-all duration-300" id="allUsersLabel">
                                        <input type="radio" name="recipient_type" value="all" checked class="w-5 h-5 text-purple-600 border-gray-300 focus:ring-purple-500" onchange="toggleRecipients('all')">
                                        <div class="ml-3 flex-1">
                                            <div class="font-semibold text-gray-800">All Users</div>
                                            <div class="text-sm text-gray-500">Send to <span class="font-bold text-purple-600">{{ $users->count() }}</span> registered users</div>
                                        </div>
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                    </label>

                                    <label class="recipient-option relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-300 hover:border-purple-200" id="selectedUsersLabel">
                                        <input type="radio" name="recipient_type" value="selected" class="w-5 h-5 text-purple-600 border-gray-300 focus:ring-purple-500" onchange="toggleRecipients('selected')">
                                        <div class="ml-3 flex-1">
                                            <div class="font-semibold text-gray-800">Selected Users</div>
                                            <div class="text-sm text-gray-500">Choose specific recipients</div>
                                        </div>
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                    </label>
                                </div>

                                <div id="selectedUsersContainer" class="hidden">
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <label class="font-medium text-gray-700">Select Recipients</label>
                                            <div class="flex gap-2">
                                                <button type="button" id="selectAllUsers" class="px-3 py-1.5 bg-white border border-purple-200 text-purple-600 rounded-lg text-xs font-medium hover:bg-purple-50 transition-colors">
                                                    Select All
                                                </button>
                                                <button type="button" id="deselectAllUsers" class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-50 transition-colors">
                                                    Clear
                                                </button>
                                            </div>
                                        </div>
                                        <select id="users-multiselect" name="selected_users[]" multiple
                                            class="w-full border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white p-2"
                                            size="8">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" class="px-3 py-2 hover:bg-purple-50 cursor-pointer rounded-lg">
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Hold Ctrl (Cmd on Mac) to select multiple users
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="button" id="previewBtn"
                                class="flex-1 px-6 py-4 bg-gray-800 text-white rounded-xl font-semibold text-sm uppercase tracking-wider hover:bg-gray-900 transition-all duration-300 flex items-center justify-center gap-2 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Preview Email
                            </button>

                            <button type="submit"
                                class="flex-1 px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl font-semibold text-sm uppercase tracking-wider hover:from-purple-700 hover:to-purple-800 transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-purple-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Send Newsletter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Quick Stats -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            Audience Overview
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-purple-50/50 rounded-xl border border-purple-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Total Users</div>
                                        <div class="text-xl font-bold text-gray-800">{{ number_format($users->count()) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-green-50/50 rounded-xl border border-green-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Verified</div>
                                        <div class="text-xl font-bold text-gray-800">{{ number_format($users->where('email_verified_at', '!=', null)->count()) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50/50 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">This Month</div>
                                        <div class="text-xl font-bold text-gray-800">{{ number_format($users->where('created_at', '>=', now()->startOfMonth())->count()) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="rounded-2xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path></svg>
                            <h3 class="font-semibold">Pro Tips</h3>
                        </div>
                        <ul class="space-y-3 text-sm text-white/90">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Use personalization with user names for better engagement</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Keep subject lines under 50 characters</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Preview before sending to check formatting</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Best send times: Tuesday-Thursday, 10 AM - 2 PM</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Recent Campaigns
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-800 truncate">Welcome Email</div>
                                    <div class="text-xs text-gray-500">Sent to 1,234 users</div>
                                </div>
                                <div class="text-xs text-gray-400">2h ago</div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-800 truncate">Platform Update</div>
                                    <div class="text-xs text-gray-500">Sent to 892 users</div>
                                </div>
                                <div class="text-xs text-gray-400">1d ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="hidden fixed top-5 right-5 p-4 rounded-xl shadow-2xl z-50 max-w-sm">
        <div class="flex items-center gap-3">
            <div id="toastIcon" class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"></div>
            <div id="toastMessage" class="text-sm font-medium"></div>
        </div>
    </div>

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/cixo6rkm79lh7ls450tnbu4yf0g4gherr9vlajdb9vecxoo1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '#content',
            height: 400,
            plugins: 'advlist autolink link image lists table preview fullscreen code help wordcount media emoticons',
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | emoticons | preview fullscreen code',
            branding: false,
            promotion: false,
            menubar: false,
            statusbar: true,
            resize: true,
            content_style: 'body { font-family: Inter, sans-serif; font-size: 15px; line-height: 1.8; color: #374151; }',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });

        // Template selection
        function selectTemplate(element, template) {
            document.querySelectorAll('.template-card').forEach(card => {
                card.classList.remove('selected');
                card.classList.remove('border-purple-300');
                card.classList.add('border-gray-200');
                card.querySelector('.check-icon').classList.add('hidden');
                card.querySelector('.check-icon').classList.remove('bg-purple-600');
                card.querySelector('.check-icon').classList.add('bg-gray-300');
            });

            element.classList.add('selected');
            element.classList.remove('border-gray-200');
            element.classList.add('border-purple-300');
            element.querySelector('.check-icon').classList.remove('hidden');
            element.querySelector('.check-icon').classList.remove('bg-gray-300');
            element.querySelector('.check-icon').classList.add('bg-purple-600');

            document.getElementById('selectedTemplate').value = template;
        }

        // Recipient toggle
        function toggleRecipients(type) {
            const allLabel = document.getElementById('allUsersLabel');
            const selectedLabel = document.getElementById('selectedUsersLabel');
            const container = document.getElementById('selectedUsersContainer');

            if (type === 'all') {
                allLabel.classList.add('border-purple-300', 'bg-purple-50/30');
                allLabel.classList.remove('border-gray-200');
                selectedLabel.classList.remove('border-purple-300', 'bg-purple-50/30');
                selectedLabel.classList.add('border-gray-200');
                container.classList.add('hidden');
            } else {
                selectedLabel.classList.add('border-purple-300', 'bg-purple-50/30');
                selectedLabel.classList.remove('border-gray-200');
                allLabel.classList.remove('border-purple-300', 'bg-purple-50/30');
                allLabel.classList.add('border-gray-200');
                container.classList.remove('hidden');
            }
        }

        // Toast notification
        function showToast(msg, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toastIcon');
            const message = document.getElementById('toastMessage');

            message.textContent = msg;

            if (type === 'success') {
                toast.className = 'fixed top-5 right-5 p-4 rounded-xl shadow-2xl z-50 max-w-sm bg-green-50 border border-green-200';
                icon.className = 'w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0';
                icon.innerHTML = '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else {
                toast.className = 'fixed top-5 right-5 p-4 rounded-xl shadow-2xl z-50 max-w-sm bg-red-50 border border-red-200';
                icon.className = 'w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0';
                icon.innerHTML = '<svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            }

            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 4000);
        }

        // User selection
        const usersSelect = document.getElementById('users-multiselect');

        document.getElementById('selectAllUsers')?.addEventListener('click', () => {
            [...usersSelect.options].forEach(opt => opt.selected = true);
            showToast('All users selected', 'success');
        });

        document.getElementById('deselectAllUsers')?.addEventListener('click', () => {
            [...usersSelect.options].forEach(opt => opt.selected = false);
            showToast('Selection cleared', 'success');
        });

        // Preview
        document.getElementById('previewBtn').addEventListener('click', () => {
            const content = tinymce.get('content').getContent();
            const subject = document.getElementById('subject').value;
            const template = document.getElementById('selectedTemplate').value;

            if (!subject || !content) {
                showToast('Please fill in subject and content first', 'error');
                return;
            }

            showToast('Sending preview...', 'success');

            fetch('{{ route("admin.newsletter.preview") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ subject, content, template })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    showToast(d.message || 'Preview sent to your email!', 'success');
                } else {
                    showToast(d.message || 'Preview failed', 'error');
                }
            })
            .catch(() => showToast('Preview failed', 'error'));
        });

        // Form submission loading state
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';
            submitBtn.disabled = true;
        });
    </script>
</x-admin-layout>