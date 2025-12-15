@auth
    @if(auth()->user()->isAdmin())
        <div id="admin-chat-widget" class="fixed bottom-6 right-6 z-50" x-data="{
                init() {
                    const pos = JSON.parse(localStorage.getItem('admin_chat_widget_pos'));
                    if (pos) {
                        this.$el.style.top = pos.top;
                        this.$el.style.left = pos.left;
                        this.$el.style.bottom = 'auto';
                        this.$el.style.right = 'auto';
                    }
                },
                isDragging: false,
                hasMoved: false,
                startX: 0,
                startY: 0,
                offsetX: 0,
                offsetY: 0,
                dragStart(e) {
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    this.startX = clientX;
                    this.startY = clientY;
                    this.hasMoved = false;
                    const rect = this.$el.getBoundingClientRect();
                    this.offsetX = clientX - rect.left;
                    this.offsetY = clientY - rect.top;
                },
                dragMove(e) {
                    if (this.startX === 0) return;
                    
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                    
                    const deltaX = Math.abs(clientX - this.startX);
                    const deltaY = Math.abs(clientY - this.startY);
                    
                    if (!this.isDragging && (deltaX > 5 || deltaY > 5)) {
                        this.isDragging = true;
                        this.hasMoved = true;
                        this.$el.style.transition = 'none';
                        this.$el.style.cursor = 'grabbing';
                    }
                    
                    if (this.isDragging) {
                        e.preventDefault();
                        const newLeft = clientX - this.offsetX;
                        const newTop = clientY - this.offsetY;
                        
                        const maxX = window.innerWidth - this.$el.offsetWidth;
                        const maxY = window.innerHeight - this.$el.offsetHeight;
                        
                        this.$el.style.left = Math.max(0, Math.min(newLeft, maxX)) + 'px';
                        this.$el.style.top = Math.max(0, Math.min(newTop, maxY)) + 'px';
                        this.$el.style.bottom = 'auto';
                        this.$el.style.right = 'auto';
                    }
                },
                dragEnd() {
                    if (this.isDragging) {
                        localStorage.setItem('admin_chat_widget_pos', JSON.stringify({
                            top: this.$el.style.top,
                            left: this.$el.style.left
                        }));
                    }
                    
                    this.$el.style.transition = '';
                    this.$el.style.cursor = '';
                    
                    const wasDragging = this.hasMoved;
                    this.isDragging = false;
                    this.startX = 0;
                    this.startY = 0;
                    
                    if (wasDragging) {
                        this.$el.dataset.justDragged = 'true';
                        setTimeout(() => {
                            delete this.$el.dataset.justDragged;
                            this.hasMoved = false;
                        }, 100);
                    } else {
                        this.hasMoved = false;
                    }
                }
             }" @mousedown="dragStart" @mousemove.window="dragMove" @mouseup.window="dragEnd" @touchstart.passive="dragStart"
             @touchmove.window="dragMove" @touchend.window="dragEnd">
            <!-- Chat Button (Floating) -->
            <button id="admin-chat-widget-toggle"
                class="relative w-16 h-16 rounded-full bg-gradient-to-br from-gray-900 to-black text-white shadow-lg hover:shadow-2xl transition-all duration-300 flex items-center justify-center group animate-float hover:scale-110"
                aria-label="Buka Chat Admin">
                <!-- Logo/Icon -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>

                <!-- Notification Badge -->
                @php
                    $unreadConversations = \App\Models\Conversation::whereHas('messages', function ($query) {
                        $query->where('is_read', false)
                            ->whereHas('user', function ($q) {
                                $q->where('role', \App\Models\User::ROLE_CUSTOMER);
                            });
                    })->count();
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
                    Chat dari Pelanggan
                </span>
            </button>

            <!-- Chat Window (Hidden by default) -->
            <div id="admin-chat-widget-window"
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
                            <h3 class="font-semibold text-sm" id="admin-chat-widget-customer-name">Chat Support</h3>
                            <p class="text-xs text-gray-300" id="admin-chat-widget-status">Pilih percakapan</p>
                        </div>
                    </div>
                    <button id="admin-chat-widget-close"
                        class="w-8 h-8 rounded-full hover:bg-white/20 flex items-center justify-center transition"
                        aria-label="Tutup Chat">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Conversations List / Messages Container -->
                <div id="admin-chat-widget-content" class="flex-1 overflow-y-auto bg-gray-50">
                    <!-- Conversations List (Default View) -->
                    <div id="admin-chat-widget-conversations" class="p-4 space-y-2">
                        @php
                            $recentConversations = \App\Models\Conversation::with(['user', 'latestMessage'])
                                ->latest('last_message_at')
                                ->take(5)
                                ->get();
                        @endphp
                        @if($recentConversations->count() > 0)
                            @foreach($recentConversations as $conv)
                                <button onclick="loadAdminConversation({{ $conv->id }})"
                                    class="w-full text-left p-3 bg-white rounded-2xl border border-gray-200 hover:border-gray-900 transition space-y-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-sm">{{ $conv->user->name }}</p>
                                        @if($conv->unreadMessagesCount() > 0)
                                            <span
                                                class="px-2 py-1 text-xs bg-red-500 text-white rounded-full">{{ $conv->unreadMessagesCount() }}</span>
                                        @endif
                                    </div>
                                    @if($conv->latestMessage)
                                        <p class="text-xs text-gray-600 line-clamp-1">{{ Str::limit($conv->latestMessage->body, 50) }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">
                                        {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : '' }}</p>
                                </button>
                            @endforeach
                            <a href="{{ route('admin.chat.index') }}"
                                class="block w-full p-3 text-center text-sm text-gray-600 hover:text-gray-900">
                                Lihat semua percakapan →
                            </a>
                        @else
                            <div class="text-center text-sm text-gray-500 py-8">
                                <p>Belum ada percakapan</p>
                            </div>
                        @endif
                    </div>

                    <!-- Messages Container (Hidden by default) -->
                    <div id="admin-chat-widget-messages" class="hidden p-4 space-y-3"></div>
                </div>

                <!-- Input Form (Hidden by default) -->
                <div id="admin-chat-widget-input-container" class="hidden border-t border-gray-200 p-4 bg-white">
                    <form id="admin-chat-widget-form" class="flex gap-2">
                        <input type="hidden" name="conversation_id" id="admin-chat-widget-conversation-id" value="">
                        <textarea name="body" id="admin-chat-widget-input" rows="2" required placeholder="Tulis balasan..."
                            class="flex-1 rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-gray-900 focus:ring-gray-900 resize-none"></textarea>
                        <button type="submit"
                            class="px-4 py-3 rounded-full bg-gray-900 text-white hover:bg-black transition self-end"
                            id="admin-chat-widget-send-btn">
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

            #admin-chat-widget-window {
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
                const toggleBtn = document.getElementById('admin-chat-widget-toggle');
                const closeBtn = document.getElementById('admin-chat-widget-close');
                const chatWindow = document.getElementById('admin-chat-widget-window');
                const chatForm = document.getElementById('admin-chat-widget-form');
                const chatInput = document.getElementById('admin-chat-widget-input');
                const messagesContainer = document.getElementById('admin-chat-widget-messages');
                const conversationsList = document.getElementById('admin-chat-widget-conversations');
                const inputContainer = document.getElementById('admin-chat-widget-input-container');
                let currentConversationId = null;
                let autoRefreshInterval = null;

                // CSRF Token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // Toggle window
                if (toggleBtn && closeBtn && chatWindow) {
                    toggleBtn.addEventListener('click', function (e) {
                        const widget = document.getElementById('admin-chat-widget');
                        if (widget && widget.dataset.justDragged) {
                            e.preventDefault();
                            e.stopPropagation();
                            return;
                        }
                        chatWindow.classList.toggle('hidden');
                        if (!chatWindow.classList.contains('hidden')) {
                            showConversationsList();
                        } else {
                            stopAutoRefresh();
                        }
                    });

                    closeBtn.addEventListener('click', function () {
                        chatWindow.classList.add('hidden');
                        stopAutoRefresh();
                        showConversationsList();
                    });

                    // Close on outside click
                    document.addEventListener('click', function (event) {
                        const widget = document.getElementById('admin-chat-widget');
                        if (widget && !widget.contains(event.target) && !chatWindow.classList.contains('hidden')) {
                            chatWindow.classList.add('hidden');
                            stopAutoRefresh();
                            showConversationsList();
                        }
                    });
                }

                // Show conversations list
                function showConversationsList() {
                    conversationsList.classList.remove('hidden');
                    messagesContainer.classList.add('hidden');
                    inputContainer.classList.add('hidden');
                    currentConversationId = null;
                    stopAutoRefresh();
                }

                // Load conversation
                window.loadAdminConversation = function (conversationId) {
                    currentConversationId = conversationId;
                    document.getElementById('admin-chat-widget-conversation-id').value = conversationId;

                    conversationsList.classList.add('hidden');
                    messagesContainer.classList.remove('hidden');
                    inputContainer.classList.remove('hidden');

                    loadMessages(conversationId);
                };

                // Load messages
                function loadMessages(conversationId) {
                    fetch(`/admin/chat/${conversationId}/messages`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Update header
                            const customerName = document.getElementById('admin-chat-widget-customer-name');
                            const status = document.getElementById('admin-chat-widget-status');
                            if (data.conversation && data.conversation.user) {
                                if (customerName) customerName.textContent = data.conversation.user.name;
                                if (status) status.textContent = data.conversation.user.email;
                            }

                            // Render messages
                            renderMessages(data.messages);

                            // Start auto-refresh
                            startAutoRefresh();
                        })
                        .catch(error => {
                            console.error('Error loading messages:', error);
                        });
                }

                // Render messages
                function renderMessages(messages) {
                    if (!messages || messages.length === 0) {
                        messagesContainer.innerHTML = '<div class="text-center text-sm text-gray-500 py-8"><p>Belum ada pesan.</p></div>';
                        return;
                    }

                    let html = '';
                    messages.forEach(message => {
                        const isAdmin = message.is_admin;
                        // Admin messages: right side, dark background, white text
                        // Customer messages: left side, light background, dark text
                        html += `
                                    <div class="flex ${isAdmin ? 'justify-end' : 'justify-start'} chat-message">
                                        <div class="max-w-[75%] space-y-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <p class="text-xs font-semibold">${message.user.name}</p>
                                                <p class="text-xs text-gray-400">${message.created_at}</p>
                                            </div>
                                            <div class="rounded-2xl px-4 py-3 ${isAdmin ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900'}">
                                                <p class="text-sm whitespace-pre-wrap ${isAdmin ? 'text-white' : 'text-gray-900'}">${escapeHtml(message.body)}</p>
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
                        if (!body || !currentConversationId) return;

                        const sendBtn = document.getElementById('admin-chat-widget-send-btn');
                        sendBtn.disabled = true;
                        sendBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                        fetch(`/admin/chat/${currentConversationId}/message`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                body: body,
                            })
                        })
                            .then(response => {
                                if (response.ok) {
                                    chatInput.value = '';
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
            });
        </script>
    @endif
@endauth