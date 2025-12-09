@extends('layouts.admin')

@section('page-title', 'Tambah Produk')

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4">
        @csrf
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Nama
            <input type="text" name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
        </label>
        <div class="grid sm:grid-cols-2 gap-4">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                <label for="category_id_create" class="block">Kategori</label>
                <select name="category_id" id="category_id_create" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}" @selected(old('category_id') == $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Harga
                <input type="number" name="price" value="{{ old('price') }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Stok
                <input type="number" name="stock" value="{{ old('stock', 0) }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Ringkasan singkat
                <input type="text" name="summary" value="{{ old('summary') }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
        </div>
        
        <!-- Discount Section -->
        <div class="border-t border-gray-200 pt-6 mt-6">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-4">Diskon Produk</p>
            <div class="grid sm:grid-cols-3 gap-4">
                <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                    Persentase Diskon (%)
                    <input type="number" name="discount_percentage" value="{{ old('discount_percentage', 0) }}" min="0" max="100" step="0.01" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada diskon</p>
                </label>
                <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                    Mulai Diskon
                    <input type="datetime-local" name="discount_starts_at" value="{{ old('discount_starts_at') }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                </label>
                <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                    Berakhir Diskon
                    <input type="datetime-local" name="discount_expires_at" value="{{ old('discount_expires_at') }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                </label>
            </div>
        </div>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Deskripsi
            <textarea name="description" rows="5" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">{{ old('description') }}</textarea>
        </label>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Spesifikasi (JSON Format)
            <textarea name="specifications" rows="8" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring font-mono text-sm" placeholder='{"Berat": "65g", "Sensor": "PixArt PAW3395", "Switches": "Omron D2FC-F-K"}'>{{ old('specifications') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Format JSON: {"Key": "Value", "Key2": "Value2"}. Contoh: {"Berat": "65g", "Sensor": "PixArt PAW3395"}</p>
        </label>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Gambar (Max 10 gambar)
            <input type="file" name="images[]" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/bmp,image/svg+xml" multiple class="mt-2 w-full rounded-2xl border border-dashed border-gray-300 px-4 py-3">
            <p class="text-xs text-gray-500 mt-1">Format yang didukung: JPEG, JPG, PNG, GIF, WEBP, BMP, SVG (Max 5MB per gambar, maksimal 10 gambar). Gambar akan otomatis di-resize dan di-crop menjadi square (800x800).</p>
        </label>
        <div class="flex flex-wrap gap-6 text-xs uppercase tracking-[0.4em] text-gray-400">
            <label class="inline-flex items-center gap-2">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                Featured
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                Aktif
            </label>
        </div>
        <div class="flex justify-end">
            <button class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring">Simpan</button>
        </div>
    </form>
@endsection

