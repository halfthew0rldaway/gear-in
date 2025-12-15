@auth
    @if(auth()->user()->isCustomer())
        <div id="chat-widget" class="fixed bottom-6 right-6 z-50">
            <!-- Chat Button (Floating) -->
            <!-- Untuk mengganti logo, ubah bagian SVG di bawah atau ganti dengan <img> tag -->
            <button id="chat-widget-toggle"
                class="relative w-16 h-16 rounded-full bg-gradient-to-br from-gray-900 to-black text-white shadow-lg hover:shadow-2xl transition-all duration-300 flex items-center justify-center group animate-float hover:scale-110"
                aria-label="Buka Chat" x-data="{
                            init() {
                                try {
                                    const widget = document.getElementById('chat-widget');
                                    const posStr = localStorage.getItem('chat_widget_pos');
                                    if (posStr) {
                                        const pos = JSON.parse(posStr);
                                        if (pos && pos.top && pos.left) {
                                            widget.style.top = pos.top;
                                            widget.style.left = pos.left;
                                            widget.style.bottom = 'auto';
                                            widget.style.right = 'auto';
                                        }
                                    }
                                } catch (e) {
                                    console.error('Error loading chat widget position:', e);
                                }
                            }}"
                        @mousedown="(e) => {
                            const widget = document.getElementById('chat-widget');
                            const clientX = e.clientX;
                            const clientY = e.clientY;
                            const startX = clientX;
                            const startY = clientY;
                            let hasMoved = false;
                            let isDragging = false;
                            const rect = widget.getBoundingClientRect();
                            const offsetX = clientX - rect.left;
                            const offsetY = clientY - rect.top;

                            const onMouseMove = (e) => {
                                const deltaX = Math.abs(e.clientX - startX);
                                const deltaY = Math.abs(e.clientY - startY);

                                if (!isDragging && (deltaX > 5 || deltaY > 5)) {
                                    isDragging = true;
                                    hasMoved = true;
                                    widget.style.transition = 'none';
                                    widget.style.cursor = 'grabbing';
                                }

                                if (isDragging) {
                                    e.preventDefault();
                                    const newLeft = e.clientX - offsetX;
                                    const newTop = e.clientY - offsetY;
                                    const maxX = window.innerWidth - widget.offsetWidth;
                                    const maxY = window.innerHeight - widget.offsetHeight;
                                    widget.style.left = Math.max(0, Math.min(newLeft, maxX)) + 'px';
                                    widget.style.top = Math.max(0, Math.min(newTop, maxY)) + 'px';
                                    widget.style.bottom = 'auto';
                                    widget.style.right = 'auto';
                                }
                            };

                            const onMouseUp = () => {
                                if (isDragging) {
                                    localStorage.setItem('chat_widget_pos', JSON.stringify({
                                        top: widget.style.top,
                                        left: widget.style.left
                                    }));
                                }
                                widget.style.transition = '';
                                widget.style.cursor = '';

                                if (hasMoved) {
                                    widget.dataset.justDragged = 'true';
                                    setTimeout(() => {
                                        delete widget.dataset.justDragged;
                                    }, 100);
                                }

                                window.removeEventListener('mousemove', onMouseMove);
                                window.removeEventListener('mouseup', onMouseUp);
                            };

                            window.addEventListener('mousemove', onMouseMove);
                            window.addEventListener('mouseup', onMouseUp);
                        }"
                        @touchstart.passive="(e) => {
                            const widget = document.getElementById('chat-widget');
                            const touch = e.touches[0];
                            const clientX = touch.clientX;
                            const clientY = touch.clientY;
                            const startX = clientX;
                            const startY = clientY;
                            let hasMoved = false;
                            let isDragging = false;
                            const rect = widget.getBoundingClientRect();
                            const offsetX = clientX - rect.left;
                            const offsetY = clientY - rect.top;

                            const onTouchMove = (e) => {
                                const touch = e.touches[0];
                                const deltaX = Math.abs(touch.clientX - startX);
                                const deltaY = Math.abs(touch.clientY - startY);

                                if (!isDragging && (deltaX > 5 || deltaY > 5)) {
                                    isDragging = true;
                                    hasMoved = true;
                                    widget.style.transition = 'none';
                                    widget.style.cursor = 'grabbing';
                                }

                                if (isDragging) {
                                    e.preventDefault();
                                    const newLeft = touch.clientX - offsetX;
                                    const newTop = touch.clientY - offsetY;
                                    const maxX = window.innerWidth - widget.offsetWidth;
                                    const maxY = window.innerHeight - widget.offsetHeight;
                                    widget.style.left = Math.max(0, Math.min(newLeft, maxX)) + 'px';
                                    widget.style.top = Math.max(0, Math.min(newTop, maxY)) + 'px';
                                    widget.style.bottom = 'auto';
                                    widget.style.right = 'auto';
                                }
                            };

                            const onTouchEnd = () => {
                                if (isDragging) {
                                    localStorage.setItem('chat_widget_pos', JSON.stringify({
                                        top: widget.style.top,
                                        left: widget.style.left
                                    }));
                                }
                                widget.style.transition = '';
                                widget.style.cursor = '';

                                if (hasMoved) {
                                    widget.dataset.justDragged = 'true';
                                    setTimeout(() => {
                                        delete widget.dataset.justDragged;
                                    }, 100);
                                }

                                window.removeEventListener('touchmove', onTouchMove);
                                window.removeEventListener('touchend', onTouchEnd);
                            };

                            window.addEventListener('touchmove', onTouchMove);
                            window.addEventListener('touchend', onTouchEnd);
                        }">
                        <!-- Logo/Icon - bisa diganti dengan gambar -->
                        <!-- Ganti SVG ini dengan gambar logo jika ingin -->
                        <!-- Contoh: <img src="{{ asset('images/chat-logo.png') }}" alt="Chat" class="w-8 h-8"> -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>

                        <!-- Notification Badge -->
                        @php
                            $unreadConversations = \App\Models\Conversation::where('user_id', auth()->id())
                                ->whereHas('messages', function ($query) {
                                    $query->where('is_read', false)
                                        ->whereHas('user', function ($q) {
                                            $q->where('role', \App\Models\User::ROLE_ADMIN);
                                        });
                                })
                                ->count();
                        @endphp
                        @if($unreadConversations > 0)
                            <span
                                class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                                {{ $unreadConversations > 9 ? '9+' : $unreadConversations }}
                            </span>
                        @endif

                        <!-- Hover Tooltip -->
                        <span
                            class="absolute right-full mr-3 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            Chat dengan Admin
                        </span>
                    </button>

                    <!-- Chat Window (Hidden by default) -->
                    <div id="chat-widget-window"
                        class="hidden absolute bottom-20 right-0 w-96 h-[600px] bg-white border border-gray-200 rounded-[32px] shadow-2xl flex flex-col overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gray-900 text-white px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-sm" id="chat-widget-admin-name">Chat Support</h3>
                                    <p class="text-xs text-gray-300" id="chat-widget-status">Admin gear-in</p>
                                </div>
                            </div>
                            <button id="chat-widget-close"
                                class="w-8 h-8 rounded-full hover:bg-white/20 flex items-center justify-center transition"
                                aria-label="Tutup Chat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        <!-- Messages Container -->
                        <div id="chat-widget-messages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
                            <div id="chat-widget-loading" class="text-center text-sm text-gray-500 py-8">
                                <p>Memuat pesan...</p>
                            </div>
                        </div>

                        <!-- Input Form -->
                        <div class="border-t border-gray-200 p-4 bg-white">
                            <form id="chat-widget-form" class="flex gap-2">
                                <input type="hidden" name="conversation_id" id="chat-widget-conversation-id" value="">
                                <textarea name="body" id="chat-widget-input" rows="2" required placeholder="Tulis pesan..."
                                    class="flex-1 rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-gray-900 focus:ring-gray-900 resize-none"></textarea>
                                <button type="submit"
                                    class="px-4 py-3 rounded-full bg-gray-900 text-white hover:bg-black transition self-end"
                                    id="chat-widget-send-btn">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <style>
                    @keyframes float {

                        0%,
                        100% {
                            transform: translateY(0px) rotate(0deg);
                        }

                        25% {
                            transform: translateY(-8px) rotate(-2deg);
                        }

                        50% {
                            transform: translateY(-12px) rotate(0deg);
                        }

                        75% {
                            transform: translateY(-8px) rotate(2deg);
                        }
                    }

                    .animate-float {
                        animation: float 4s ease-in-out infinite;
                    }

                    #chat-widget-window {
                        animation: slideUp 0.3s ease-out;
                    }

                    @keyframes slideUp {
                        from {
                            opacity: 0;
                            transform: translateY(20px) scale(0.95);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0) scale(1);
                        }
                    }

                    /* Pulse animation for notification badge */
                    @keyframes pulse-ring {
                        0% {
                            transform: scale(0.8);
                            opacity: 1;
                        }

                        50% {
                            transform: scale(1.1);
                            opacity: 0.7;
                        }

                        100% {
                            transform: scale(0.8);
                            opacity: 1;
                        }
                    }

                    #chat-widget-toggle:hover .animate-pulse {
                        animation: pulse-ring 1.5s ease-in-out infinite;
                    }

                    .chat-message {
                        animation: fadeIn 0.3s ease-in;
                    }

                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                            transform: translateY(10px);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const toggleBtn = document.getElementById('chat-widget-toggle');
                        const closeBtn = document.getElementById('chat-widget-close');
                        const chatWindow = document.getElementById('chat-widget-window');
                        const chatForm = document.getElementById('chat-widget-form');
                        const chatInput = document.getElementById('chat-widget-input');
                        const messagesContainer = document.getElementById('chat-widget-messages');
                        const loadingDiv = document.getElementById('chat-widget-loading');
                        const conversationIdInput = document.getElementById('chat-widget-conversation-id');
                        let currentConversationId = null;
                        let autoRefreshInterval = null;

                        // CSRF Token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                        // Toggle window
                        if (toggleBtn && closeBtn && chatWindow) {
                            toggleBtn.addEventListener('click', function (e) {
                                const widget = document.getElementById('chat-widget');
                                if (widget && widget.dataset.justDragged) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return;
                                }
                                chatWindow.classList.toggle('hidden');
                                if (!chatWindow.classList.contains('hidden')) {
                                    loadConversation();
                                } else {
                                    stopAutoRefresh();
                                }
                            });

                            closeBtn.addEventListener('click', function () {
                                chatWindow.classList.add('hidden');
                                stopAutoRefresh();
                            });

                            // Close on outside click
                            document.addEventListener('click', function (event) {
                                const widget = document.getElementById('chat-widget');
                                if (widget && !widget.contains(event.target) && !chatWindow.classList.contains('hidden')) {
                                    chatWindow.classList.add('hidden');
                                    stopAutoRefresh();
                                }
                            });
                        }

                        // Load conversation and messages
                        function loadConversation() {
                            if (loadingDiv) loadingDiv.style.display = 'block';

                            // Get latest conversation or create new
                            fetch('{{ route('chat.index') }}', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            })
                                .then(response => response.text())
                                .then(html => {
                                    // Try to get conversation ID from page or create new
                                    if (currentConversationId) {
                                        loadMessages(currentConversationId);
                                    } else {
                                        // Show empty state
                                        if (loadingDiv) loadingDiv.style.display = 'none';
                                        messagesContainer.innerHTML = '<div class="text-center text-sm text-gray-500 py-8"><p>Mulai percakapan baru dengan admin</p></div>';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error loading conversation:', error);
                                    if (loadingDiv) loadingDiv.style.display = 'none';
                                });
                        }

                        // Load messages
                        function loadMessages(conversationId) {
                            fetch(`/chat/${conversationId}/messages`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (loadingDiv) loadingDiv.style.display = 'none';
                                    currentConversationId = data.conversation.id;
                                    conversationIdInput.value = currentConversationId;

                                    // Update header
                                    const adminName = document.getElementById('chat-widget-admin-name');
                                    const status = document.getElementById('chat-widget-status');
                                    if (data.conversation.admin) {
                                        if (adminName) adminName.textContent = data.conversation.admin.name;
                                        if (status) status.textContent = 'Admin gear-in';
                                    }

                                    // Render messages
                                    renderMessages(data.messages);

                                    // Start auto-refresh
                                    startAutoRefresh();
                                })
                                .catch(error => {
                                    console.error('Error loading messages:', error);
                                    if (loadingDiv) loadingDiv.style.display = 'none';
                                });
                        }

                        // Render messages
                        function renderMessages(messages) {
                            if (!messages || messages.length === 0) {
                                messagesContainer.innerHTML = '<div class="text-center text-sm text-gray-500 py-8"><p>Belum ada pesan. Mulai percakapan!</p></div>';
                                return;
                            }

                            let html = '';
                            messages.forEach(message => {
                                const isAdmin = message.is_admin;
                                html += `
                                            <div class="flex ${isAdmin ? 'justify-start' : 'justify-end'} chat-message">
                                                <div class="max-w-[75%] space-y-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <p class="text-xs font-semibold">${message.user.name}</p>
                                                        <p class="text-xs text-gray-400">${message.created_at}</p>
                                                    </div>
                                                    <div class="rounded-2xl px-4 py-3 ${isAdmin ? 'bg-gray-100 text-gray-900' : 'bg-gray-900 text-white'}">
                                                        <p class="text-sm whitespace-pre-wrap ${isAdmin ? 'text-gray-900' : 'text-white'}">${escapeHtml(message.body)}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                            });
                            messagesContainer.innerHTML = html;
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }

                        // Send message
                        if (chatForm) {
                            chatForm.addEventListener('submit', function (e) {
                                e.preventDefault();

                                const body = chatInput.value.trim();
                                if (!body) return;

                                const sendBtn = document.getElementById('chat-widget-send-btn');
                                sendBtn.disabled = true;
                                sendBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                                fetch('{{ route('chat.send-ajax') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': csrfToken,
                                    },
                                    body: JSON.stringify({
                                        body: body,
                                        conversation_id: currentConversationId,
                                    })
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            chatInput.value = '';
                                            currentConversationId = data.conversation_id;
                                            conversationIdInput.value = currentConversationId;

                                            // Reload messages
                                            loadMessages(currentConversationId);
                                        } else {
                                            window.customAlert('Gagal mengirim pesan. Silakan coba lagi.', 'Gagal Mengirim');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error sending message:', error);
                                        window.customAlert('Gagal mengirim pesan. Silakan coba lagi.', 'Gagal Mengirim');
                                    })
                                    .finally(() => {
                                        sendBtn.disabled = false;
                                        sendBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>';
                                    });
                            });
                        }

                        // Auto-refresh messages
                        function startAutoRefresh() {
                            stopAutoRefresh();
                            if (currentConversationId) {
                                autoRefreshInterval = setInterval(() => {
                                    loadMessages(currentConversationId);
                                }, 3000);
                            }
                        }

                        function stopAutoRefresh() {
                            if (autoRefreshInterval) {
                                clearInterval(autoRefreshInterval);
                                autoRefreshInterval = null;
                            }
                        }

                        // Utility function
                        function escapeHtml(text) {
                            const div = document.createElement('div');
                            div.textContent = text;
                            return div.innerHTML;
                        }

                        // Load conversation on open if already has one
                        @php
                            $latestConversation = \App\Models\Conversation::where('user_id', auth()->id())
                                ->latest('last_message_at')
                                ->first();
                        @endphp
                          @if($latestConversation)
                            currentConversationId = {{ $latestConversation->id }};
                                conversationIdInput.value = {{ $latestConversation->id }};
                        @endif
                    });
                    </script>
    @endif
@endauth
