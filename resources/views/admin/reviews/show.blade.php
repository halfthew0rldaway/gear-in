@extends('layouts.admin')

@section('page-title', 'Detail Review')

@section('content')
    <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-6">
        <div>
            <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Reviews
            </a>
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Produk</p>
                <p class="text-lg font-semibold">{{ $review->product->name }}</p>
                <a href="{{ route('products.show', $review->product) }}" target="_blank" class="text-xs text-gray-500 hover:text-gray-900">Lihat Produk</a>
            </div>
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Customer</p>
                <p class="text-lg font-semibold">{{ $review->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $review->user->email }}</p>
                <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 space-y-4">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Rating</p>
                <div class="flex items-center gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="text-2xl {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                    @endfor
                    <span class="text-sm text-gray-500 ml-2">{{ $review->rating }}/5</span>
                </div>
            </div>

            @if($review->comment)
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Komentar</p>
                    <p class="text-sm text-gray-700 bg-gray-50 rounded-2xl p-4">{{ $review->comment }}</p>
                </div>
            @endif

            @if($review->admin_reply)
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Balasan Admin</p>
                    <div class="bg-gray-50 rounded-2xl p-4 space-y-2">
                        <p class="text-sm text-gray-700 font-medium">{{ $review->admin_reply }}</p>
                        @if($review->adminRepliedBy)
                            <p class="text-xs text-gray-500">— {{ $review->adminRepliedBy->name }}, {{ $review->admin_replied_at->format('d M Y, H:i') }}</p>
                        @endif
                    </div>
                </div>
            @endif

            @if(!$review->admin_reply)
                <div class="border-t border-gray-100 pt-6">
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-4">Balas Review</p>
                    <form action="{{ route('admin.reviews.reply', $review) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-xs text-gray-500 mb-2 block">Balasan</label>
                            <textarea name="admin_reply" rows="4" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900" placeholder="Tulis balasan untuk review ini..." required></textarea>
                        </div>
                        <button type="submit" class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">Kirim Balasan</button>
                    </form>
                </div>
            @else
                <div class="border-t border-gray-100 pt-6">
                    <p class="text-xs text-gray-500">Review sudah dibalas. Untuk mengubah balasan, silakan edit melalui database atau hapus dan buat ulang.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

