@extends('layouts.storefront')

@section('title', 'Chat · gear-in')

@section('content')
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('chat.index') }}" class="text-sm text-gray-500 hover:text-gray-900 mb-2 inline-block">← Kembali ke daftar chat</a>
                <h1 class="text-2xl font-semibold">{{ $conversation->subject ?? 'Percakapan' }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    @if($conversation->admin)
                        Admin: {{ $conversation->admin->name }}
                    @else
                        Menunggu admin...
                    @endif
                </p>
            </div>
            @if($conversation->status === 'open')
                <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full uppercase">Buka</span>
            @elseif($conversation->status === 'pending')
                <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full uppercase">Menunggu</span>
            @else
                <span class="px-3 py-1 text-xs bg-gray-100 text-gray-800 rounded-full uppercase">Tutup</span>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4" id="messages-container" style="max-height: 500px; overflow-y: auto;">
            @forelse($conversation->messages as $message)
                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] space-y-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-xs font-semibold">{{ $message->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $message->created_at->format('H:i') }}</p>
                        </div>
                        <div class="rounded-2xl px-4 py-3 {{ $message->user_id === auth()->id() ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900' }}">
                            <p class="text-sm whitespace-pre-wrap {{ $message->user_id === auth()->id() ? 'text-white' : 'text-gray-900' }}">{{ $message->body }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Belum ada pesan. Mulai percakapan dengan mengirim pesan di bawah.</p>
            @endforelse
        </div>

        @if($conversation->status !== 'closed')
        <form action="{{ route('chat.send', $conversation) }}" method="POST" class="bg-white border border-gray-200 rounded-[32px] p-6" id="message-form">
            @csrf
            <div class="flex gap-3">
                <textarea name="body" rows="2" required placeholder="Tulis pesan..." class="flex-1 rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 resize-none" id="message-input"></textarea>
                <button type="submit" class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition self-end">
                    Kirim
                </button>
            </div>
        </form>
        @else
        <div class="bg-gray-100 border border-gray-200 rounded-[32px] p-6 text-center">
            <p class="text-sm text-gray-600">Percakapan ini sudah ditutup. Buat percakapan baru untuk melanjutkan.</p>
        </div>
        @endif
    </section>

    @push('scripts')
    <script>
        // Auto-refresh messages every 3 seconds
        let autoRefreshInterval;
        let isSubmitting = false;
        
        function refreshMessages() {
            // Don't refresh if form is being submitted
            if (isSubmitting) return;
            
            fetch('{{ route('chat.show', $conversation) }}', {
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
                clearInterval(autoRefreshInterval);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messages-container');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            autoRefreshInterval = setInterval(refreshMessages, 3000);

            const messageForm = document.getElementById('message-form');
            if (messageForm) {
                messageForm.addEventListener('submit', function(e) {
                    isSubmitting = true;
                    clearInterval(autoRefreshInterval);
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

