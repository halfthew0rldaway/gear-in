@extends('layouts.admin')

@section('page-title', 'Tambah Review Marketing')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-8">
            <a href="{{ route('admin.products.show', $product) }}"
                class="text-sm text-gray-500 hover:text-gray-900 mb-2 inline-block">← Kembali ke Produk</a>
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-1">Review Marketing</p>
            <h1 class="text-3xl font-semibold">Tambah Review untuk {{ $product->name }}</h1>
            <p class="text-sm text-gray-500 mt-2">Buat review untuk tujuan marketing tanpa memerlukan pembelian aktual.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-8">
            <form action="{{ route('admin.marketing-reviews.store', $product) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="user_id" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">
                        Pelanggan <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id"
                        class="w-full rounded-xl border-gray-200 text-sm focus:border-gray-900 focus:ring-gray-900"
                        required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Pilih pelanggan yang akan menjadi penulis review ini.</p>
                </div>

                <div>
                    <label for="rating" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">
                        Rating <span class="text-red-500">*</span>
                    </label>
                    <select name="rating" id="rating"
                        class="w-full rounded-xl border-gray-200 text-sm focus:border-gray-900 focus:ring-gray-900"
                        required>
                        <option value="5" {{ old('rating', '5') == '5' ? 'selected' : '' }}>5 - Excellent ★★★★★</option>
                        <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 - Very Good ★★★★☆</option>
                        <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 - Good ★★★☆☆</option>
                        <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 - Fair ★★☆☆☆</option>
                        <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 - Poor ★☆☆☆☆</option>
                    </select>
                    @error('rating')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="comment" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">
                        Komentar
                    </label>
                    <textarea name="comment" id="comment" rows="6"
                        class="w-full rounded-xl border-gray-200 text-sm focus:border-gray-900 focus:ring-gray-900 resize-none"
                        placeholder="Tulis komentar review...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Komentar bersifat opsional, tetapi sangat disarankan untuk review
                        yang lebih meyakinkan.</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-blue-800 mb-1">Catatan Marketing</p>
                            <p class="text-xs text-blue-700">Review ini akan ditampilkan sebagai review normal dari
                                pelanggan yang dipilih. Review marketing tidak terkait dengan pesanan tertentu dan dibuat
                                untuk tujuan promosi produk.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit"
                        class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple">
                        Tambah Review
                    </button>
                    <a href="{{ route('admin.products.show', $product) }}"
                        class="px-6 py-3 rounded-full border border-gray-300 text-gray-600 text-xs uppercase tracking-[0.4em] hover:border-gray-900 hover:text-gray-900 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection