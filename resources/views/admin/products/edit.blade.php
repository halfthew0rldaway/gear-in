@extends('layouts.admin')

@section('page-title', 'Edit Produk')

@section('content')
@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-1">Manajemen Produk</p>
            <h1 class="text-3xl font-semibold">Edit Produk</h1>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-600 text-xs uppercase tracking-[0.2em] hover:bg-gray-50 transition">
                Kembali
            </a>
            <button type="submit" form="product-form" class="px-5 py-2.5 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.2em] hover:bg-black transition btn-ripple">
                Simpan Perubahan
            </button>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="product-form">
        @csrf
        @method('PUT')
        
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content (2 cols) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Basic Info -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-8">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Informasi Utama</h2>
                    <div class="space-y-6">
                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Nama Produk</span>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                        </label>
                        
                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Ringkasan</span>
                            <input type="text" name="summary" value="{{ old('summary', $product->summary) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                        </label>

                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Deskripsi</span>
                            <textarea name="description" rows="5" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">{{ old('description', $product->description) }}</textarea>
                        </label>

                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Spesifikasi (JSON)</span>
                            <textarea name="specifications" rows="6" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 font-mono text-sm">{{ old('specifications', $product->specifications ? json_encode($product->specifications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
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
                            <div class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-2xl hover:bg-gray-50 transition">
                                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-sm text-gray-500">Klik untuk upload gambar baru</span>
                            </div>
                        </label>
                    </div>

                    @if ($product->images->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach ($product->images as $image)
                                <div class="relative group">
                                    <img src="{{ Storage::url($image->image_path) }}" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                                    <label class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded-xl cursor-pointer">
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="sr-only peer">
                                        <span class="text-white text-xs uppercase tracking-wider font-medium peer-checked:text-red-400">Hapus</span>
                                    </label>
                                    <!-- Checked Overlay -->
                                    <div class="absolute inset-0 bg-red-500/20 border-2 border-red-500 rounded-xl hidden peer-checked:block pointer-events-none"></div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Stock History -->
                 <div class="bg-white border border-gray-200 rounded-[32px] p-8">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Riwayat Stok</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">
                                <tr>
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Tipe</th>
                                    <th class="py-3 px-4">Jumlah</th>
                                    <th class="py-3 px-4">Ref</th>
                                    <th class="py-3 px-4">User</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($product->stockMovements as $movement)
                                    <tr>
                                         <td class="py-3 px-4 text-gray-500">{{ $movement->created_at->format('d M Y H:i') }}</td>
                                         <td class="py-3 px-4">
                                            @if($movement->type === 'manual')
                                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-600 text-[10px] uppercase font-bold tracking-wider">Manual</span>
                                            @elseif($movement->type === 'order')
                                                <span class="px-2 py-1 rounded bg-blue-50 text-blue-600 text-[10px] uppercase font-bold tracking-wider">Order</span>
                                            @elseif($movement->type === 'cancel')
                                                <span class="px-2 py-1 rounded bg-yellow-50 text-yellow-600 text-[10px] uppercase font-bold tracking-wider">Cancel</span>
                                            @else
                                                <span class="px-2 py-1 rounded bg-gray-50 text-gray-600 text-[10px] uppercase font-bold tracking-wider">{{ $movement->type }}</span>
                                            @endif
                                         </td>
                                         <td class="py-3 px-4 font-mono font-semibold {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                             {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                         </td>
                                         <td class="py-3 px-4 font-mono text-gray-600">{{ $movement->reference_id ?? '-' }}</td>
                                         <td class="py-3 px-4 text-gray-600">{{ $movement->user->name ?? 'System' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-400 text-sm">Belum ada riwayat stok.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar (1 col) -->
            <div class="space-y-8">
                <!-- Status Card -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Status</h2>
                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer transition">
                            <span class="text-sm font-medium text-gray-700">Aktif (Dijual)</span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900"></div>
                            </div>
                        </label>
                        <label class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer transition">
                            <span class="text-sm font-medium text-gray-700">Featured (Unggulan)</span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Price & Category -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Harga & Kategori</h2>
                    <div class="space-y-5">
                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Kategori</span>
                            <select name="category_id" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" @selected(old('category_id', $product->category_id) == $id)>{{ $name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Harga (Rp)</span>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                        </label>

                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Stok</span>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                        </label>
                    </div>
                </div>

                <!-- Discount -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Diskon</h2>
                    <div class="space-y-5">
                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Persentase (%)</span>
                            <input type="number" name="discount_percentage" value="{{ old('discount_percentage', $product->discount_percentage) }}" min="0" max="100" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="block">
                                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-2 block">Mulai</span>
                                <input type="datetime-local" name="discount_starts_at" value="{{ old('discount_starts_at', $product->discount_starts_at ? $product->discount_starts_at->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-xl border border-gray-200 px-2 py-2 text-xs focus:border-gray-900 focus:ring-gray-900">
                            </label>
                            <label class="block">
                                <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-2 block">Selesai</span>
                                <input type="datetime-local" name="discount_expires_at" value="{{ old('discount_expires_at', $product->discount_expires_at ? $product->discount_expires_at->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-xl border border-gray-200 px-2 py-2 text-xs focus:border-gray-900 focus:ring-gray-900">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@endsection

