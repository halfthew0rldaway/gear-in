@extends('layouts.admin')

@section('page-title', 'Tambah Produk')

@section('content')
    @section('content')
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-1">Manajemen Produk</p>
                <h1 class="text-3xl font-semibold">Tambah Produk</h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.products.index') }}"
                    class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-600 text-xs uppercase tracking-[0.2em] hover:bg-gray-50 transition">
                    Kembali
                </a>
                <button type="submit" form="product-form"
                    class="px-5 py-2.5 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.2em] hover:bg-black transition btn-ripple">
                    Simpan Produk
                </button>
            </div>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
            @csrf

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content (2 cols) -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Basic Info -->
                    <div class="bg-white border border-gray-200 rounded-[32px] p-8">
                        <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Informasi Utama</h2>
                        <div class="space-y-6">
                            <label class="block">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Nama
                                    Produk</span>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                            </label>

                            <label class="block">
                                <span
                                    class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Ringkasan</span>
                                <input type="text" name="summary" value="{{ old('summary') }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                            </label>

                            <label class="block">
                                <span
                                    class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Deskripsi</span>
                                <textarea name="description" rows="5"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">{{ old('description') }}</textarea>
                            </label>

                            <label class="block">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Spesifikasi
                                    (JSON)</span>
                                <textarea name="specifications" rows="6"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 font-mono text-sm"
                                    placeholder='{"Berat": "65g", "Sensor": "PixArt PAW3395", "Switches": "Omron D2FC-F-K"}'>{{ old('specifications') }}</textarea>
                                <p class="text-xs text-gray-500 mt-2">Format: {"Key": "Value"}</p>
                            </label>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="bg-white border border-gray-200 rounded-[32px] p-8">
                        <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Galeri</h2>

                        <div class="mb-6">
                            <label class="block w-full cursor-pointer">
                                <input type="file" name="images[]" multiple accept="image/*" class="hidden">
                                <div
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-2xl hover:bg-gray-50 transition">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="text-sm text-gray-500">Klik untuk upload gambar baru (Max 10)</span>
                                </div>
                            </label>
                            <p class="text-xs text-gray-500 mt-2">Format: JPEG, JPG, PNG, GIF, WEBP (Max 5MB). Otomatis crop
                                square (800x800).</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (1 col) -->
                <div class="space-y-8">
                    <!-- Status Card -->
                    <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                        <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Status</h2>
                        <div class="space-y-4">
                            <label
                                class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer transition">
                                <span class="text-sm font-medium text-gray-700">Aktif (Dijual)</span>
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900">
                                    </div>
                                </div>
                            </label>
                            <label
                                class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer transition">
                                <span class="text-sm font-medium text-gray-700">Featured (Unggulan)</span>
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ old('is_featured') ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Price & Category -->
                    <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                        <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Harga & Kategori</h2>
                        <div class="space-y-5">
                            <label class="block">
                                <span
                                    class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Kategori</span>
                                <select name="category_id"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                                    @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}" @selected(old('category_id') == $id)>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="block">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Harga
                                    (Rp)</span>
                                <input type="number" name="price" value="{{ old('price') }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                            </label>

                            <label class="block">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Stok</span>
                                <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                            </label>
                        </div>
                    </div>

                    <!-- Discount -->
                    <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                        <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Diskon</h2>
                        <div class="space-y-5">
                            <label class="block">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Persentase
                                    (%)</span>
                                <input type="number" name="discount_percentage" value="{{ old('discount_percentage') }}" min="0"
                                    max="100"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="block">
                                    <span
                                        class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-2 block">Mulai</span>
                                    <input type="datetime-local" name="discount_starts_at"
                                        value="{{ old('discount_starts_at') }}"
                                        class="w-full rounded-xl border border-gray-200 px-2 py-2 text-xs focus:border-gray-900 focus:ring-gray-900">
                                </label>
                                <label class="block">
                                    <span
                                        class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-2 block">Selesai</span>
                                    <input type="datetime-local" name="discount_expires_at"
                                        value="{{ old('discount_expires_at') }}"
                                        class="w-full rounded-xl border border-gray-200 px-2 py-2 text-xs focus:border-gray-900 focus:ring-gray-900">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endsection
@endsection