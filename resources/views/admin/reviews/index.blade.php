@extends('layouts.admin')

@section('page-title', 'Reviews')

@section('content')
    <div class="bg-white border border-gray-200 rounded-[32px] overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-[0.4em] text-gray-400">
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Rating</th>
                    <th class="px-6 py-4">Komentar</th>
                    <th class="px-6 py-4">Balasan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($reviews as $review)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $review->product->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $review->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-sm {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $review->comment ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->admin_reply)
                                <span class="text-xs text-green-600 font-medium">Sudah dibalas</span>
                            @else
                                <span class="text-xs text-gray-400">Belum dibalas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.reviews.show', $review) }}" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $reviews->links() }}
@endsection

