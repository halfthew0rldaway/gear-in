@extends('layouts.admin')

@section('page-title', 'Produk')

@section('content')
    <div class="flex justify-end">
        <a href="{{ route('admin.products.create') }}" class="text-xs uppercase tracking-[0.4em] border border-gray-900 px-4 py-2 rounded-full hover:bg-gray-900 hover:text-white transition">Tambah Produk</a>
    </div>
    <div class="bg-white border border-gray-200 rounded-[32px] overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-[0.4em] text-gray-400">
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Stok</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->summary }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $product->category->name }}</td>
                        <td class="px-6 py-4">{{ $product->formatted_price }}</td>
                        <td class="px-6 py-4">{{ $product->stock }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block ml-4">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs uppercase tracking-[0.4em] text-red-500 hover:text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
@endsection

