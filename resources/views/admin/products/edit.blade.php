@extends('layouts.admin')

@section('page-title', 'Edit Produk')

@section('content')
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4">
        @csrf
        @method('PUT')
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Nama
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
        </label>
        <div class="grid sm:grid-cols-2 gap-4">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Kategori
                <select name="category_id" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}" @selected(old('category_id', $product->category_id) == $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Harga
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
            </label>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Stok
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
            </label>
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Ringkasan singkat
                <input type="text" name="summary" value="{{ old('summary', $product->summary) }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
            </label>
        </div>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Deskripsi
            <textarea name="description" rows="5" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">{{ old('description', $product->description) }}</textarea>
        </label>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Gambar Baru
            <input type="file" name="image" class="mt-2 w-full rounded-2xl border border-dashed border-gray-300 px-4 py-3">
        </label>
        @if ($product->image_path)
            <p class="text-xs text-gray-500">Gambar saat ini: {{ $product->image_path }}</p>
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
            <button class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">Simpan</button>
        </div>
    </form>
@endsection

