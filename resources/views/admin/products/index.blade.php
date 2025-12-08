@extends('layouts.admin')

@section('page-title', 'Produk')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Produk</h1>
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.products.index') }}" method="GET" class="relative">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="w-64 rounded-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm focus-ring">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-900 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="text-xs uppercase tracking-[0.4em] border border-gray-900 px-4 py-2 rounded-full hover:bg-gray-900 hover:text-white transition btn-ripple focus-ring">Tambah Produk</a>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-[32px] overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-[0.4em] text-gray-400">
                    <th class="px-6 py-4">
                        <a href="{{ route('admin.products.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'name', 'sort_order' => request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-900 transition link-underline">
                            Produk
                            @if(request('sort_by') == 'name')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4">
                        <a href="{{ route('admin.products.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'category', 'sort_order' => request('sort_by') == 'category' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-900 transition link-underline">
                            Kategori
                            @if(request('sort_by') == 'category')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4">
                        <a href="{{ route('admin.products.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'price', 'sort_order' => request('sort_by') == 'price' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-900 transition link-underline">
                            Harga
                            @if(request('sort_by') == 'price')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4">
                        <a href="{{ route('admin.products.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'stock', 'sort_order' => request('sort_by') == 'stock' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-900 transition link-underline">
                            Stok
                            @if(request('sort_by') == 'stock')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($products as $product)
                    <tr class="scroll-reveal">
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->summary }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $product->category->name }}</td>
                        <td class="px-6 py-4">{{ $product->formatted_price }}</td>
                        <td class="px-6 py-4">{{ $product->stock }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900 link-underline focus-ring">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block ml-4">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs uppercase tracking-[0.4em] text-red-500 hover:text-red-600 focus-ring">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                            @if(request('q'))
                                Tidak ada produk yang ditemukan untuk "{{ request('q') }}".
                            @else
                                Belum ada produk.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
@endsection

