@extends('layouts.admin')

@section('page-title', 'Chat')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Percakapan dengan Pelanggan</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola semua percakapan dari pelanggan</p>
            </div>
            @if($unreadCount > 0)
                <span class="px-4 py-2 bg-red-500 text-white rounded-full text-sm font-semibold">
                    {{ $unreadCount }} Pesan Belum Dibaca
                </span>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6">
            <div class="divide-y divide-gray-100">
                @forelse($conversations as $conversation)
                    <a href="{{ route('admin.chat.show', $conversation) }}" class="block p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <p class="font-semibold">{{ $conversation->user->name }}</p>
                                    <span class="text-xs text-gray-500">{{ $conversation->user->email }}</span>
                                    @if($conversation->status === 'open')
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Buka</span>
                                    @elseif($conversation->status === 'pending')
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Menunggu</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">Tutup</span>
                                    @endif
                                </div>
                                <p class="text-sm font-medium text-gray-900 mb-1">{{ $conversation->subject ?? 'Pertanyaan' }}</p>
                                @if($conversation->latestMessage)
                                    <p class="text-sm text-gray-600 line-clamp-1">{{ Str::limit($conversation->latestMessage->body, 100) }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">{{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : $conversation->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="ml-4 text-right">
                                @if($conversation->unreadMessagesCount() > 0)
                                    <span class="px-3 py-1 text-xs bg-red-500 text-white rounded-full font-semibold">{{ $conversation->unreadMessagesCount() }}</span>
                                @endif
                                @if($conversation->admin)
                                    <p class="text-xs text-gray-500 mt-2">Admin: {{ $conversation->admin->name }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-gray-500 py-8">Belum ada percakapan.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $conversations->links() }}
            </div>
        </div>
    </div>
@endsection

