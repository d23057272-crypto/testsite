<x-user-layout :title="'Ticket #' . $ticket->ticket_id">
    <style>
        .chat-scroll::-webkit-scrollbar { width: 4px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background: rgba(156,163,175,0.3); border-radius: 4px; }
        @keyframes msgSlide { from { opacity:0; transform: translateY(10px); } to { opacity:1; transform: translateY(0); } }
        .msg-anim { animation: msgSlide 0.35s cubic-bezier(0.34, 1.56, 0.64, 1); }
        @keyframes pulse-ring { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(2); opacity: 0; } }
        .live-dot::before { content: ''; position: absolute; width: 100%; height: 100%; border-radius: 50%; background: currentColor; animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite; }
    </style>

    <main class="w-full mx-auto bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 h-[calc(100vh-64px)] md:h-screen flex flex-col">
        <!-- Header -->
        <header class="flex-shrink-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 px-4 py-3 z-20">
            <div class="flex items-center gap-3">
                <a href="{{ route('support') }}" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 transition-all">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-base font-semibold text-gray-900 dark:text-white truncate">{{ $ticket->subject }}</h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-[11px] font-mono text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $ticket->ticket_id }}</span>
                        <span id="statusBadge" class="inline-flex items-center gap-1.5 text-[11px] font-medium px-2 py-0.5 rounded-full
                            @if($ticket->status == 'open') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                            @elseif($ticket->status == 'answered') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                            @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 @endif">
                            @if($ticket->status == 'open')<span class="relative w-1.5 h-1.5 rounded-full bg-amber-500 live-dot"></span> Open
                            @elseif($ticket->status == 'answered')<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Answered
                            @else<span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Closed @endif
                        </span>
                    </div>
                </div>
                @if(!$ticket->isClosed())
                    <form action="{{ route('support.close', $ticket->ticket_id) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" onclick="return confirm('Close this ticket?')" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-red-100 dark:hover:bg-red-900/30 text-gray-500 dark:text-gray-400 hover:text-red-500 flex items-center justify-center transition-all" title="Close Ticket">
                            <i class="fas fa-lock text-sm"></i>
                        </button>
                    </form>
                @endif
            </div>
        </header>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto chat-scroll px-4 py-4 space-y-4" id="messagesContainer">
            @php $lastReplyId = 0; @endphp
            <div class="flex justify-center"><div class="bg-gray-200/60 dark:bg-gray-700/40 rounded-full px-4 py-1.5"><p class="text-[11px] text-gray-500 dark:text-gray-400">Ticket created {{ $ticket->created_at->format('M d, Y \a\t g:i A') }}</p></div></div>
            @foreach($replies as $reply)
                @php $lastReplyId = max($lastReplyId, $reply->id); @endphp
                @if($reply->isFromAdmin())
                    <div class="flex gap-3 msg-anim" data-reply-id="{{ $reply->id }}">
                        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-md shadow-purple-500/20 ring-2 ring-white dark:ring-gray-800">
                            <i class="fas fa-headset text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0 max-w-[78%]">
                            <div class="flex items-baseline gap-2 mb-1"><span class="text-xs font-semibold text-gray-700 dark:text-gray-200">Support Team</span><span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $reply->created_at->format('g:i A') }}</span></div>
                            <div class="bg-white dark:bg-gray-800 rounded-2xl rounded-tl-md px-4 py-3 shadow-sm border border-gray-100 dark:border-gray-700/50">
                                <p class="text-sm text-gray-700 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</p>
                                @if($reply->attachment)
                                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                        <a href="{{ route('support.attachment', basename($reply->attachment)) }}" class="inline-flex items-center gap-2 text-xs text-purple-600 dark:text-purple-400 hover:text-purple-700 font-medium transition-colors bg-purple-50 dark:bg-purple-900/20 px-3 py-1.5 rounded-lg">
                                            <i class="fas fa-paperclip"></i><span>Attachment</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex gap-3 flex-row-reverse msg-anim" data-reply-id="{{ $reply->id }}">
                        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md shadow-blue-500/20 ring-2 ring-white dark:ring-gray-800">
                            <span class="text-white text-xs font-bold">{{ strtoupper(substr($reply->user?->name ?? 'Y', 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0 max-w-[78%] flex flex-col items-end">
                            <div class="flex items-baseline gap-2 mb-1 flex-row-reverse"><span class="text-xs font-semibold text-gray-700 dark:text-gray-200">{{ $reply->user?->name ?? 'You' }}</span><span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $reply->created_at->format('g:i A') }}</span></div>
                            <div class="bg-blue-600 rounded-2xl rounded-tr-md px-4 py-3 shadow-md shadow-blue-600/20">
                                <p class="text-sm text-white leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</p>
                                @if($reply->attachment)
                                    <div class="mt-3 pt-3 border-t border-blue-400/40">
                                        <a href="{{ route('support.attachment', basename($reply->attachment)) }}" class="inline-flex items-center gap-2 text-xs text-blue-100 hover:text-white font-medium transition-colors bg-white/10 px-3 py-1.5 rounded-lg">
                                            <i class="fas fa-paperclip"></i><span>Attachment</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            @if($replies->isEmpty())
                <div class="flex flex-col items-center justify-center py-16" id="noRepliesMsg">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center mb-4 shadow-inner">
                        <i class="fas fa-comments text-2xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">No messages yet</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Send your first message below</p>
                </div>
            @endif
        </div>

        <!-- New Message Floating Alert -->
        <div id="newMessageAlert" class="hidden fixed bottom-24 left-1/2 -translate-x-1/2 z-30">
            <button onclick="scrollToBottom(true)" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-5 py-2.5 rounded-full shadow-xl shadow-blue-600/30 flex items-center gap-2 transition-all hover:scale-105">
                <i class="fas fa-arrow-down animate-bounce"></i> New message received
            </button>
        </div>

        <!-- Input -->
        @if(!$ticket->isClosed())
            <div class="flex-shrink-0 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border-t border-gray-200 dark:border-gray-700 p-3 z-20">
                <div id="attachmentPreview" class="hidden mb-3">
                    <div class="flex items-center gap-3 p-2.5 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                        <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0"><i class="fas fa-file text-purple-600 dark:text-purple-400 text-sm"></i></div>
                        <div class="flex-1 min-w-0"><p id="attachmentName" class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate"></p><p id="attachmentSize" class="text-[10px] text-gray-500 dark:text-gray-400"></p></div>
                        <button type="button" onclick="removeAttachment()" class="w-7 h-7 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 text-red-500 flex items-center justify-center transition-colors"><i class="fas fa-times text-xs"></i></button>
                    </div>
                </div>
                <form id="replyForm" enctype="multipart/form-data" class="flex items-end gap-2">
                    @csrf
                    <label for="attachment" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-400 cursor-pointer transition-all flex-shrink-0" title="Attach file">
                        <i class="fas fa-paperclip"></i>
                        <input type="file" name="attachment" id="attachment" class="hidden" onchange="handleFileSelect(this)" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt,.zip">
                    </label>
                    <div class="flex-1 relative">
                        <textarea name="message" id="message" rows="1" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-2xl px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 resize-none transition-all" placeholder="Type a message..." oninput="autoResize(this)" style="min-height: 42px; max-height: 120px;"></textarea>
                    </div>
                    <button type="submit" id="submitBtn" class="w-10 h-10 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-90 text-white flex items-center justify-center transition-all flex-shrink-0 shadow-lg shadow-blue-600/25 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane text-sm" id="btnIcon"></i>
                        <svg id="btnLoading" class="hidden animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    </button>
                </form>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1.5 text-center">Max: 10MB</p>
            </div>
        @else
            <div class="flex-shrink-0 bg-gray-100 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center"><i class="fas fa-lock text-gray-500 dark:text-gray-400 text-xs"></i></div>
                    <span class="text-sm text-gray-600 dark:text-gray-300">This ticket is closed.</span>
                    <a href="{{ route('support.create') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-semibold">Open new ticket</a>
                </div>
            </div>
        @endif
    </main>

    <script>
        let lastReplyId = {{ $lastReplyId }};
        let isTabActive = true;
        let pollInterval;
        let isSubmitting = false;
        const ticketId = '{{ $ticket->ticket_id }}';
        const csrfToken = '{{ csrf_token() }}';
        const replyUrl = '{{ route("support.reply", $ticket->ticket_id) }}';
        const pollUrl = '{{ route("support.poll", $ticket->ticket_id) }}';
        const POLL_INTERVAL = 2500;

        document.addEventListener('visibilitychange', () => { isTabActive = !document.hidden; });
        window.addEventListener('load', () => { scrollToBottom(false); });

        function autoResize(el) { el.style.height = ''; el.style.height = Math.min(el.scrollHeight, 120) + 'px'; }
        function formatFileSize(b) { if (!b) return '0 B'; const k=1024, s=['B','KB','MB','GB'], i=Math.floor(Math.log(b)/Math.log(k)); return parseFloat((b/Math.pow(k,i)).toFixed(1))+' '+s[i]; }

        function handleFileSelect(input) {
            const p = document.getElementById('attachmentPreview'), n = document.getElementById('attachmentName'), s = document.getElementById('attachmentSize');
            if (input.files && input.files[0]) { n.textContent = input.files[0].name; s.textContent = formatFileSize(input.files[0].size); p.classList.remove('hidden'); document.getElementById('message').focus(); }
            else p.classList.add('hidden');
        }
        function removeAttachment() { document.getElementById('attachment').value = ''; document.getElementById('attachmentPreview').classList.add('hidden'); }

        function scrollToBottom(animate = true) {
            const c = document.getElementById('messagesContainer');
            if (animate) c.scrollTo({ top: c.scrollHeight, behavior: 'smooth' });
            else c.scrollTop = c.scrollHeight;
            document.getElementById('newMessageAlert').classList.add('hidden');
        }

        function isNearBottom() {
            const c = document.getElementById('messagesContainer');
            return c.scrollHeight - c.clientHeight - c.scrollTop < 150;
        }

        // ===== SEND MESSAGE =====
        document.getElementById('replyForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            if (isSubmitting) return;
            const msg = document.getElementById('message').value.trim();
            const file = document.getElementById('attachment').files[0];
            if (!msg && !file) { shakeInput(); return; }

            isSubmitting = true;
            const btn = document.getElementById('submitBtn'), icon = document.getElementById('btnIcon'), load = document.getElementById('btnLoading');
            btn.disabled = true; icon.classList.add('hidden'); load.classList.remove('hidden');

            const fd = new FormData();
            fd.append('_token', csrfToken); fd.append('message', msg);
            if (file) fd.append('attachment', file);

            try {
                const res = await fetch(replyUrl, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }});
                const data = await res.json();
                if (data.success) {
                    appendUserMessage({ id: data.reply_id, message: msg || '(Attachment)', attachment: data.attachment ? basename(data.attachment) : null, created_at: 'Just now' });
                    lastReplyId = Math.max(lastReplyId, data.reply_id);
                    document.getElementById('message').value = ''; autoResize(document.getElementById('message')); removeAttachment();
                    scrollToBottom(true);
                } else {
                    toast(data.message || 'Failed to send', 'error');
                }
            } catch(err) { console.error(err); toast('Network error. Please retry.', 'error'); }
            finally { isSubmitting = false; btn.disabled = false; icon.classList.remove('hidden'); load.classList.add('hidden'); }
        });

        function shakeInput() {
            const el = document.getElementById('message');
            el.classList.add('ring-2', 'ring-red-400');
            setTimeout(() => el.classList.remove('ring-2', 'ring-red-400'), 600);
        }

        function toast(text, type='info') {
            const t = document.createElement('div');
            t.className = `fixed top-4 left-1/2 -translate-x-1/2 z-50 px-5 py-2.5 rounded-full text-sm font-medium shadow-2xl transition-all duration-300 ${type==='error'?'bg-red-500 text-white':'bg-gray-800 text-white'}`;
            t.innerHTML = `<i class="fas ${type==='error'?'fa-exclamation-circle':'fa-info-circle'} mr-2"></i>${text}`;
            document.body.appendChild(t);
            requestAnimationFrame(() => { t.style.opacity = '1'; t.style.transform = 'translate(-50%, 0)'; });
            setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translate(-50%, -12px)'; setTimeout(() => t.remove(), 300); }, 3500);
        }

        function basename(p) { return p.split('/').pop().split('\\\\').pop(); }

        // ===== APPEND USER MESSAGE (immediately after send) =====
        function appendUserMessage(reply) {
            const c = document.getElementById('messagesContainer');
            document.getElementById('noRepliesMsg')?.remove();
            const html = `
                <div class="flex gap-3 flex-row-reverse msg-anim" data-reply-id="${reply.id}">
                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md shadow-blue-500/20 ring-2 ring-white dark:ring-gray-800">
                        <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'Y', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0 max-w-[78%] flex flex-col items-end">
                        <div class="flex items-baseline gap-2 mb-1 flex-row-reverse"><span class="text-xs font-semibold text-gray-700 dark:text-gray-200">You</span><span class="text-[10px] text-gray-400 dark:text-gray-500">${reply.created_at}</span></div>
                        <div class="bg-blue-600 rounded-2xl rounded-tr-md px-4 py-3 shadow-md shadow-blue-600/20">
                            <p class="text-sm text-white leading-relaxed whitespace-pre-wrap">${escapeHtml(reply.message)}</p>
                            ${reply.attachment ? `<div class="mt-3 pt-3 border-t border-blue-400/40"><a href="/support/attachment/${reply.attachment}" class="inline-flex items-center gap-2 text-xs text-blue-100 hover:text-white font-medium bg-white/10 px-3 py-1.5 rounded-lg"><i class="fas fa-paperclip"></i><span>Attachment</span></a></div>` : ''}
                        </div>
                    </div>
                </div>`;
            c.insertAdjacentHTML('beforeend', html);
        }

        // ===== APPEND ADMIN MESSAGE (from polling) =====
        function appendAdminMessage(reply) {
            const c = document.getElementById('messagesContainer');
            document.getElementById('noRepliesMsg')?.remove();
            const html = `
                <div class="flex gap-3 msg-anim" data-reply-id="${reply.id}">
                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-md shadow-purple-500/20 ring-2 ring-white dark:ring-gray-800">
                        <i class="fas fa-headset text-white text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0 max-w-[78%]">
                        <div class="flex items-baseline gap-2 mb-1"><span class="text-xs font-semibold text-gray-700 dark:text-gray-200">Support Team</span><span class="text-[10px] text-gray-400 dark:text-gray-500">${reply.created_at}</span></div>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl rounded-tl-md px-4 py-3 shadow-sm border border-gray-100 dark:border-gray-700/50">
                            <p class="text-sm text-gray-700 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">${escapeHtml(reply.message)}</p>
                            ${reply.attachment ? `<div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700"><a href="/support/attachment/${reply.attachment}" class="inline-flex items-center gap-2 text-xs text-purple-600 dark:text-purple-400 hover:text-purple-700 font-medium bg-purple-50 dark:bg-purple-900/20 px-3 py-1.5 rounded-lg"><i class="fas fa-paperclip"></i><span>Attachment</span></a></div>` : ''}
                        </div>
                    </div>
                </div>`;
            c.insertAdjacentHTML('beforeend', html);
        }

        function escapeHtml(t) { const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }

        // ===== POLLING =====
        async function pollForNewMessages() {
            if (!isTabActive) return;
            try {
                const res = await fetch(`${pollUrl}?last_reply_id=${lastReplyId}`, {
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();

                if (data.replies && data.replies.length > 0) {
                    let hasNew = false;
                    data.replies.forEach(r => {
                        if (!document.querySelector(`[data-reply-id="${r.id}"]`)) {
                            if (r.sender_type === 'admin') appendAdminMessage(r);
                            else appendUserMessage({...r, user_name: r.user_name || 'You'});
                            hasNew = true;
                        }
                        lastReplyId = Math.max(lastReplyId, r.id);
                    });

                    if (hasNew) {
                        if (isNearBottom()) scrollToBottom(true);
                        else document.getElementById('newMessageAlert').classList.remove('hidden');

                        // Update status badge if ticket was answered
                        if (data.ticket_status === 'answered') {
                            const badge = document.getElementById('statusBadge');
                            if (badge && !badge.innerHTML.includes('Answered')) {
                                badge.className = 'inline-flex items-center gap-1.5 text-[11px] font-medium px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400';
                                badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Answered';
                            }
                        }
                    }
                }
            } catch(e) { /* silent */ }
        }

        pollInterval = setInterval(pollForNewMessages, POLL_INTERVAL);
        setTimeout(pollForNewMessages, 1000);
        window.addEventListener('beforeunload', () => clearInterval(pollInterval));
    </script>
</x-user-layout>