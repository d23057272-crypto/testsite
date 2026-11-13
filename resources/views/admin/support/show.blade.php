@php
    $lastReplyId = 0;
@endphp
<x-admin-layout>
    <div class="container px-6 mx-auto py-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Dashboard</a>
            <i class="ri-arrow-right-s-line mx-2"></i>
            <a href="{{ route('admin.support.index') }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Support</a>
            <i class="ri-arrow-right-s-line mx-2"></i>
            <span class="text-gray-700 dark:text-gray-300 font-medium">Ticket #{{ $ticket->ticket_id }}</span>
        </nav>

        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $ticket->subject }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    By <a href="{{ route('admin.support.user', $ticket->user_id) }}" class="text-purple-600 dark:text-purple-400 hover:underline font-medium">{{ $ticket->user?->name ?? 'Deleted User' }}</a> 
                    on {{ $ticket->created_at->format('M d, Y \a\t g:i A') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if($ticket->status != 'closed')
                    <form action="{{ route('admin.support.close', $ticket) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="ri-lock-line mr-2"></i> Close
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.support.reopen', $ticket) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="ri-lock-unlock-line mr-2"></i> Reopen
                        </button>
                    </form>
                @endif
                <form action="{{ route('admin.support.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Delete this ticket permanently?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="ri-delete-bin-line mr-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Conversation -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Original Message -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="ri-message-3-line text-purple-600 dark:text-purple-400"></i>
                            <h3 class="font-semibold text-gray-800 dark:text-white">Original Message</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $ticket->description ?? 'No description provided.' }}</p>
                    </div>
                </div>

                <!-- Conversation Thread -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="ri-chat-3-line text-purple-600 dark:text-purple-400"></i>
                            <h3 class="font-semibold text-gray-800 dark:text-white">Conversation</h3>
                            <span class="ml-auto text-xs text-gray-500 dark:text-gray-400">{{ $ticket->replies->count() }} {{ Str::plural('reply', $ticket->replies->count()) }}</span>
                        </div>
                    </div>
                    <div class="p-6 space-y-4" id="conversationThread">
                        @forelse($ticket->replies as $reply)
                            @php $lastReplyId = max($lastReplyId, $reply->id); @endphp
                            <div class="flex gap-4 {{ $reply->sender_type == 'admin' ? 'flex-row-reverse' : '' }}" data-reply-id="{{ $reply->id }}">
                                <div class="flex-shrink-0">
                                    @if($reply->sender_type == 'admin')
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white">
                                            <i class="ri-customer-service-2-line"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-sm font-bold">
                                            {{ strtoupper(substr($reply->user?->name ?? '?', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 max-w-[85%]">
                                    <div class="rounded-2xl px-5 py-4 {{ $reply->sender_type == 'admin' ? 'bg-purple-600 text-white rounded-tr-none' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-tl-none' }}">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-semibold text-sm">{{ $reply->sender_type == 'admin' ? 'Support Team' : ($reply->user?->name ?? 'Deleted User') }}</span>
                                            <span class="text-xs {{ $reply->sender_type == 'admin' ? 'text-purple-200' : 'text-gray-500 dark:text-gray-400' }}">{{ $reply->created_at->format('M d, Y g:i A') }}</span>
                                        </div>
                                        <p class="text-sm leading-relaxed">{{ $reply->message }}</p>
                                        @if($reply->attachment)
                                            <div class="mt-3 pt-3 {{ $reply->sender_type == 'admin' ? 'border-t border-purple-500' : 'border-t border-gray-200 dark:border-gray-600' }}">
                                                <a href="{{ route('admin.support.attachment', basename($reply->attachment)) }}" 
                                                   class="inline-flex items-center text-sm {{ $reply->sender_type == 'admin' ? 'text-purple-200 hover:text-white' : 'text-purple-600 dark:text-purple-400 hover:text-purple-800' }} transition-colors">
                                                    <i class="ri-attachment-2 mr-1.5"></i>
                                                    Download Attachment
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8" id="noRepliesMsg">
                                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-3">
                                    <i class="ri-chat-off-line text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No replies yet. Be the first to respond!</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- New Message Alert -->
                    <div id="newMessageAlert" class="hidden px-6 py-3 bg-blue-50 dark:bg-blue-900/20 border-t border-blue-100 dark:border-blue-800">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-700 dark:text-blue-300 font-medium">
                                <i class="ri-notification-3-line mr-1"></i> New message received!
                            </span>
                            <button type="button" onclick="scrollToBottom()" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                View <i class="ri-arrow-down-line"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Reply Form -->
                @if($ticket->status != 'closed')
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <i class="ri-reply-line text-purple-600 dark:text-purple-400"></i>
                                <h3 class="font-semibold text-gray-800 dark:text-white">Add Reply</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <form id="replyForm" enctype="multipart/form-data" class="space-y-4">
                                @csrf

                                <div id="attachmentPreview" class="hidden">
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <i class="ri-file-3-line text-2xl text-purple-600"></i>
                                        <div class="flex-1 min-w-0">
                                            <p id="attachmentName" class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate"></p>
                                            <p id="attachmentSize" class="text-xs text-gray-500 dark:text-gray-400"></p>
                                        </div>
                                        <button type="button" onclick="removeAttachment()" class="w-8 h-8 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 text-red-500 flex items-center justify-center transition-colors" title="Remove attachment">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <textarea name="message" id="message" rows="4" 
                                              class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                                              placeholder="Type your reply here... (optional if attaching a file)"
                                              maxlength="5000"></textarea>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">You can send a reply with text, an attachment, or both.</p>
                                </div>

                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <label for="attachment" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg cursor-pointer transition-colors">
                                            <i class="ri-attachment-2 mr-2"></i> Attach File
                                        </label>
                                        <input type="file" name="attachment" id="attachment" class="hidden" onchange="handleFileSelect(this)" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt,.zip">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Max: 10MB | JPG, PNG, PDF, DOC, TXT, ZIP</span>
                                    </div>
                                    <button type="submit" id="submitBtn" class="inline-flex items-center px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span id="btnText"><i class="ri-send-plane-line mr-2"></i> Send Reply</span>
                                        <span id="btnLoading" class="hidden">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Sending...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-100 dark:bg-gray-700/50 rounded-xl p-6 flex items-center gap-3">
                        <i class="ri-information-line text-xl text-gray-500 dark:text-gray-400"></i>
                        <p class="text-gray-600 dark:text-gray-300 font-medium">This ticket is closed. Reopen it to add replies.</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-white">User Information</h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                            {{ strtoupper(substr($ticket->user?->name ?? '?', 0, 1)) }}
                        </div>
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-1">{{ $ticket->user?->name ?? 'Deleted User' }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1"><i class="ri-mail-line mr-1"></i> {{ $ticket->user?->email ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400"><i class="ri-phone-line mr-1"></i> {{ $ticket->user?->phone ?? 'No phone number' }}</p>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('admin.support.user', $ticket->user_id) }}" class="block w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="ri-ticket-line mr-2"></i> View All Tickets
                            </a>
                           <a href="{{ route('admin.users.show', $ticket->user_id) }}" class="block w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
    <i class="ri-user-line mr-2"></i> View Profile
</a>
                        </div>
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-white">Ticket Details</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                            @if($ticket->status == 'open')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Open
                                </span>
                            @elseif($ticket->status == 'answered')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Answered
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Closed
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Priority</span>
                            @if(($ticket->priority ?? 'normal') == 'high')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                    <i class="ri-fire-line mr-1"></i>High
                                </span>
                            @elseif(($ticket->priority ?? 'normal') == 'medium')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                    <i class="ri-arrow-up-line mr-1"></i>Medium
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                    Low
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Created</span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $ticket->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Last Reply</span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300" id="lastReplyTime">{{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : 'Never' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-white">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <a href="{{ route('admin.support.index') }}" class="block w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors text-center">
                            <i class="ri-arrow-left-line mr-2"></i> Back to Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lastReplyId = {{ $lastReplyId }};
        let isTabActive = true;
        let pollInterval;
        let isSubmitting = false;
        const ticketId = '{{ $ticket->ticket_id }}';
        const csrfToken = '{{ csrf_token() }}';
        const POLL_INTERVAL = 3000;
        const replyUrl = '{{ route("admin.support.reply", $ticket) }}';
        // FIXED: Use named route for admin polling instead of hardcoded user URL
        const pollUrl = '{{ route("admin.support.poll", $ticket) }}';

        document.addEventListener('visibilitychange', function() {
            isTabActive = !document.hidden;
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function handleFileSelect(input) {
            const preview = document.getElementById('attachmentPreview');
            const nameEl = document.getElementById('attachmentName');
            const sizeEl = document.getElementById('attachmentSize');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                nameEl.textContent = file.name;
                sizeEl.textContent = formatFileSize(file.size);
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        function removeAttachment() {
            document.getElementById('attachment').value = '';
            document.getElementById('attachmentPreview').classList.add('hidden');
        }

        document.getElementById('replyForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            if (isSubmitting) return;

            const message = document.getElementById('message').value.trim();
            const attachment = document.getElementById('attachment').files[0];

            if (!message && !attachment) {
                alert('Please enter a message or attach a file before sending.');
                return;
            }

            isSubmitting = true;
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');

            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('message', message);
            if (attachment) formData.append('attachment', attachment);

            try {
                const response = await fetch(replyUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Server error');
                const data = await response.json();

                if (data.success) {
                    appendReplyToThread({
                        id: data.reply_id,
                        message: message || '(File attached)',
                        sender_type: 'admin',
                        attachment: data.attachment ? basename(data.attachment) : null,
                        user_name: 'Support Team',
                        created_at: 'Just now'
                    });

                    lastReplyId = Math.max(lastReplyId, data.reply_id);
                    document.getElementById('message').value = '';
                    removeAttachment();
                    document.getElementById('lastReplyTime').textContent = 'Just now';
                    scrollToBottom();
                } else {
                    alert(data.message || 'Failed to send reply');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to send reply. Please try again.');
            } finally {
                isSubmitting = false;
                submitBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        });

        function basename(path) {
            return path.split('/').pop().split('\\').pop();
        }

        function appendReplyToThread(reply) {
            const thread = document.getElementById('conversationThread');
            const noRepliesMsg = document.getElementById('noRepliesMsg');
            if (noRepliesMsg) noRepliesMsg.remove();

            const isAdmin = reply.sender_type === 'admin';
            const flexClass = isAdmin ? 'flex-row-reverse' : '';
            const avatarBg = isAdmin ? 'bg-gradient-to-br from-purple-500 to-indigo-600' : 'bg-gradient-to-br from-gray-400 to-gray-600';
            const avatarIcon = isAdmin ? '<i class="ri-customer-service-2-line"></i>' : reply.user_name.charAt(0).toUpperCase();
            const bubbleClass = isAdmin 
                ? 'bg-purple-600 text-white rounded-tr-none' 
                : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-tl-none';
            const timeClass = isAdmin ? 'text-purple-200' : 'text-gray-500 dark:text-gray-400';
            const attachmentHtml = reply.attachment ? `
                <div class="mt-3 pt-3 ${isAdmin ? 'border-t border-purple-500' : 'border-t border-gray-200 dark:border-gray-600'}">
                    <a href="/admin/support-tickets/attachment/${reply.attachment}" class="inline-flex items-center text-sm ${isAdmin ? 'text-purple-200 hover:text-white' : 'text-purple-600 dark:text-purple-400 hover:text-purple-800'} transition-colors">
                        <i class="ri-attachment-2 mr-1.5"></i> Download Attachment
                    </a>
                </div>
            ` : '';

            const html = `
                <div class="flex gap-4 ${flexClass}" data-reply-id="${reply.id}">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full ${avatarBg} flex items-center justify-center text-white text-sm font-bold">
                            ${avatarIcon}
                        </div>
                    </div>
                    <div class="flex-1 max-w-[85%]">
                        <div class="rounded-2xl px-5 py-4 ${bubbleClass}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-sm">${isAdmin ? 'Support Team' : reply.user_name}</span>
                                <span class="text-xs ${timeClass}">${reply.created_at}</span>
                            </div>
                            <p class="text-sm leading-relaxed">${escapeHtml(reply.message)}</p>
                            ${attachmentHtml}
                        </div>
                    </div>
                </div>
            `;
            thread.insertAdjacentHTML('beforeend', html);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function scrollToBottom() {
            const thread = document.getElementById('conversationThread');
            thread.scrollTop = thread.scrollHeight;
            document.getElementById('newMessageAlert').classList.add('hidden');
        }

        async function pollForNewMessages() {
            if (!isTabActive) return;

            try {
                const response = await fetch(`${pollUrl}?last_reply_id=${lastReplyId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) return;
                const data = await response.json();

                if (data.replies && data.replies.length > 0) {
                    const thread = document.getElementById('conversationThread');
                    const noRepliesMsg = document.getElementById('noRepliesMsg');
                    if (noRepliesMsg) noRepliesMsg.remove();

                    let hasNew = false;
                    data.replies.forEach(reply => {
                        if (!document.querySelector(`[data-reply-id="${reply.id}"]`)) {
                            appendReplyToThread(reply);
                            hasNew = true;
                        }
                        lastReplyId = Math.max(lastReplyId, reply.id);
                    });

                    if (hasNew) {
                        const isScrolledToBottom = thread.scrollHeight - thread.clientHeight <= thread.scrollTop + 100;
                        if (!isScrolledToBottom) {
                            document.getElementById('newMessageAlert').classList.remove('hidden');
                        } else {
                            scrollToBottom();
                        }
                        document.getElementById('lastReplyTime').textContent = 'Just now';
                    }
                }
            } catch (error) {
                // Silently fail
            }
        }

        pollInterval = setInterval(pollForNewMessages, POLL_INTERVAL);
        setTimeout(pollForNewMessages, 2000);

        window.addEventListener('beforeunload', function() {
            clearInterval(pollInterval);
        });
    </script>
</x-admin-layout>