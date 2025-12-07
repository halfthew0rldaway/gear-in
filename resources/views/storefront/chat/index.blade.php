@extends('layouts.storefront')

@section('title', 'Chat · gear-in')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Chat</p>
            <h1 class="text-4xl font-semibold">Riwayat Percakapan</h1>
            <p class="text-sm text-gray-600 mt-2">Lihat semua percakapan Anda dengan admin gear-in</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6">
            <a href="{{ route('chat.index') }}?new=1" class="block w-full px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] text-center hover:bg-black transition mb-6">
                + Percakapan Baru
            </a>

            @if($conversations->count() > 0)
                <div class="space-y-6">
                    @php
                        $groupedConversations = $conversations->groupBy(function($conversation) {
                            return $conversation->last_message_at 
                                ? $conversation->last_message_at->format('Y-m-d')
                                : $conversation->created_at->format('Y-m-d');
                        });
                    @endphp

                    @foreach($groupedConversations as $date => $conversationsGroup)
                        <div class="space-y-4">
                            <!-- Date Header -->
                            <div class="flex items-center gap-4">
                                <div class="flex-1 h-px bg-gray-200"></div>
                                <div class="px-4 py-2 bg-gray-100 rounded-full">
                                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-[0.2em]">
                                        @php
                                            $dateObj = \Carbon\Carbon::parse($date);
                                            $today = \Carbon\Carbon::today();
                                            $yesterday = \Carbon\Carbon::yesterday();
                                            
                                            if ($dateObj->isToday()) {
                                                echo 'Hari Ini';
                                            } elseif ($dateObj->isYesterday()) {
                                                echo 'Kemarin';
                                            } elseif ($dateObj->isCurrentWeek()) {
                                                echo $dateObj->format('l'); // Day name
                                            } elseif ($dateObj->isCurrentYear()) {
                                                echo $dateObj->format('d M'); // Day Month
                                            } else {
                                                echo $dateObj->format('d M Y'); // Full date
                                            }
                                        @endphp
                                    </p>
                                </div>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>

                            <!-- Conversations for this date -->
                            <div class="space-y-3">
                                @foreach($conversationsGroup as $conversation)
                                    <a href="{{ route('chat.show', $conversation) }}" class="block p-4 border border-gray-200 rounded-2xl hover:border-gray-900 transition group">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <p class="font-semibold group-hover:text-gray-900">{{ $conversation->subject ?? 'Pertanyaan' }}</p>
                                                    @if($conversation->status === 'open')
                                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Buka</span>
                                                    @elseif($conversation->status === 'pending')
                                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Menunggu</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">Tutup</span>
                                                    @endif
                                                    @if($conversation->admin)
                                                        <span class="text-xs text-gray-500">• Admin: {{ $conversation->admin->name }}</span>
                                                    @endif
                                                </div>
                                                @if($conversation->latestMessage)
                                                    <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ Str::limit($conversation->latestMessage->body, 150) }}</p>
                                                @endif
                                                <div class="flex items-center gap-4 text-xs text-gray-400">
                                                    <span>{{ $conversation->last_message_at ? $conversation->last_message_at->format('H:i') : $conversation->created_at->format('H:i') }}</span>
                                                    <span>•</span>
                                                    <span>{{ $conversation->messages->count() }} pesan</span>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex flex-col items-end gap-2">
                                                @if($conversation->unreadMessagesCount() > 0)
                                                    <span class="px-3 py-1 text-xs bg-red-500 text-white rounded-full font-semibold">
                                                        {{ $conversation->unreadMessagesCount() }} baru
                                                    </span>
                                                @endif
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-900 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500 mb-2">Belum ada percakapan</p>
                    <p class="text-sm text-gray-400">Mulai percakapan baru dengan admin untuk mendapatkan bantuan</p>
                </div>
            @endif
        </div>
    </section>
@endsection
