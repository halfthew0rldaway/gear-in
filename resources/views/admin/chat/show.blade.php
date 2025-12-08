@extends('layouts.admin')

@section('page-title', 'Chat')

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-white border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.chat.index') }}" class="text-sm text-gray-500 hover:text-gray-900 mb-2 inline-block">← Kembali ke daftar chat</a>
                <h2 class="text-2xl font-semibold">{{ $conversation->subject ?? 'Percakapan' }}</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pelanggan: <span class="font-semibold">{{ $conversation->user->name }}</span> ({{ $conversation->user->email }})
                </p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.chat.update-status', $conversation) }}" method="POST" class="flex items-center gap-2" id="status-form">
                    @csrf
                    @method('PATCH')
                    <label for="chat-status-{{ $conversation->id }}" class="sr-only">Status Chat</label>
                    <select name="status" id="chat-status-{{ $conversation->id }}" onchange="submitStatusForm()" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900">
                        <option value="open" {{ $conversation->status === 'open' ? 'selected' : '' }}>Buka</option>
                        <option value="pending" {{ $conversation->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="closed" {{ $conversation->status === 'closed' ? 'selected' : '' }}>Tutup</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4" id="messages-container" style="max-height: 500px; overflow-y: auto;">
            @forelse($conversation->messages as $message)
                <div class="flex {{ $message->user->isAdmin() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] space-y-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-xs font-semibold">{{ $message->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $message->created_at->format('d M Y, H:i') }}</p>
                            @if($message->is_read && $message->user->isCustomer())
                                <span class="text-xs text-gray-400">✓ Dibaca</span>
                            @endif
                        </div>
                        <div class="rounded-2xl px-4 py-3 {{ $message->user->isAdmin() ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900' }}">
                            <p class="text-sm whitespace-pre-wrap {{ $message->user->isAdmin() ? 'text-white' : 'text-gray-900' }}">{{ $message->body }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Belum ada pesan.</p>
            @endforelse
        </div>

        @if($conversation->status !== 'closed')
        <form action="{{ route('admin.chat.send', $conversation) }}" method="POST" class="bg-white border border-gray-200 rounded-[32px] p-6" id="message-form">
            @csrf
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="flex gap-3">
                <textarea name="body" rows="2" required placeholder="Tulis balasan..." class="flex-1 rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 resize-none" id="message-input">{{ old('body') }}</textarea>
                <button type="submit" class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition self-end">
                    Kirim
                </button>
            </div>
        </form>
        @else
        <div class="bg-gray-100 border border-gray-200 rounded-[32px] p-6 text-center">
            <p class="text-sm text-gray-600">Percakapan ini sudah ditutup.</p>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Auto-refresh messages every 3 seconds
        let autoRefreshInterval;
        let isSubmitting = false;
        
        function refreshMessages() {
            // Don't refresh if form is being submitted
            if (isSubmitting) return;
            
            fetch('{{ route('admin.chat.show', $conversation) }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newMessagesContainer = doc.getElementById('messages-container');
                if (newMessagesContainer) {
                    const currentContainer = document.getElementById('messages-container');
                    const wasAtBottom = currentContainer.scrollHeight - currentContainer.scrollTop <= currentContainer.clientHeight + 100;
                    currentContainer.innerHTML = newMessagesContainer.innerHTML;
                    if (wasAtBottom) {
                        currentContainer.scrollTop = currentContainer.scrollHeight;
                    }
                }
            })
            .catch(error => {
                console.error('Error refreshing messages:', error);
                // Stop auto-refresh on error
                clearInterval(autoRefreshInterval);
            });
        }

        function submitStatusForm() {
            const form = document.getElementById('status-form');
            if (form) {
                isSubmitting = true;
                clearInterval(autoRefreshInterval);
                form.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messages-container');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Start auto-refresh
            autoRefreshInterval = setInterval(refreshMessages, 3000);

            const messageForm = document.getElementById('message-form');
            if (messageForm) {
                messageForm.addEventListener('submit', function(e) {
                    isSubmitting = true;
                    // Stop auto-refresh during submission
                    clearInterval(autoRefreshInterval);
                    
                    // Let form submit normally
                    // After page reload, auto-refresh will start again
                });
            }
        });

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(autoRefreshInterval);
            } else if (!isSubmitting) {
                autoRefreshInterval = setInterval(refreshMessages, 3000);
            }
        });
    </script>
    @endpush
@endsection

