@extends('layouts.admin')

@section('page-title', 'Kategori Produk')

@section('content')
    <div class="flex justify-end">
        <a href="{{ route('admin.categories.create') }}" class="text-xs uppercase tracking-[0.4em] border border-gray-900 px-4 py-2 rounded-full hover:bg-gray-900 hover:text-white transition">Tambah</a>
    </div>
    <div class="bg-white border border-gray-200 rounded-[32px] overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-[0.4em] text-gray-400">
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($categories as $category)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $category->name }}</p>
                            <p class="text-xs text-gray-500">{{ $category->description }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $category->products_count }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs uppercase tracking-[0.4em] {{ $category->is_active ? 'text-gray-900' : 'text-gray-400' }}">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block ml-4">
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
    {{ $categories->links() }}
@endsection

