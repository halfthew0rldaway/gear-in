@extends('layouts.admin')

@section('page-title', 'Edit Produk')

@section('content')
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4">
        @csrf
        @method('PUT')
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Nama
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
        </label>
        <div class="grid sm:grid-cols-2 gap-4">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Kategori
                <select name="category_id" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}" @selected(old('category_id', $product->category_id) == $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Harga
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Stok
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Ringkasan singkat
                <input type="text" name="summary" value="{{ old('summary', $product->summary) }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
        </div>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Deskripsi
            <textarea name="description" rows="5" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">{{ old('description', $product->description) }}</textarea>
        </label>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Spesifikasi (JSON Format)
            <textarea name="specifications" rows="8" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring font-mono text-sm" placeholder='{"Berat": "65g", "Sensor": "PixArt PAW3395", "Switches": "Omron D2FC-F-K"}'>{{ old('specifications', $product->specifications ? json_encode($product->specifications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Format JSON: {"Key": "Value", "Key2": "Value2"}. Contoh: {"Berat": "65g", "Sensor": "PixArt PAW3395"}</p>
        </label>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Tambah Gambar Baru (Max 10 gambar total)
            <input type="file" name="images[]" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/bmp,image/svg+xml" multiple class="mt-2 w-full rounded-2xl border border-dashed border-gray-300 px-4 py-3">
            <p class="text-xs text-gray-500 mt-1">Format yang didukung: JPEG, JPG, PNG, GIF, WEBP, BMP, SVG (Max 5MB per gambar). Gambar akan otomatis di-resize dan di-crop menjadi square (800x800).</p>
        </label>
        @if ($product->images->count() > 0)
            <div class="mt-4">
                <p class="text-xs text-gray-500 mb-2">Gambar saat ini ({{ $product->images->count() }}/10):</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($product->images as $image)
                        <div class="relative">
                            @php
                                $imageUrl = \Illuminate\Support\Facades\Storage::url($image->image_path);
                            @endphp
                            <img src="{{ $imageUrl }}" alt="Product image" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                            <label class="absolute top-2 right-2">
                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="text-xs text-red-600 ml-1">Hapus</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="flex flex-wrap gap-6 text-xs uppercase tracking-[0.4em] text-gray-400">
            <label class="inline-flex items-center gap-2">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                Featured
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                Aktif
            </label>
        </div>
        <div class="flex justify-end">
            <button class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring">Simpan</button>
        </div>
    </form>
@endsection

