@extends('layouts.admin')

@section('page-title', 'Kategori Produk')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Kategori</h1>
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="relative">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="w-64 rounded-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm focus-ring">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-900 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
            <a href="{{ route('admin.categories.create') }}" class="text-xs uppercase tracking-[0.4em] border border-gray-900 px-4 py-2 rounded-full hover:bg-gray-900 hover:text-white transition btn-ripple focus-ring">Tambah Kategori</a>
        </div>
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
                @forelse ($categories as $category)
                    <tr class="scroll-reveal">
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $category->name }}</p>
                            <p class="text-xs text-gray-500">{{ $category->description }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $category->products_count }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs uppercase tracking-[0.4em] {{ $category->is_active ? 'text-gray-900' : 'text-gray-400' }}">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900 link-underline focus-ring">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block ml-4">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs uppercase tracking-[0.4em] text-red-500 hover:text-red-600 focus-ring">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                            @if(request('search'))
                                Tidak ada kategori yang ditemukan untuk "{{ request('search') }}".
                            @else
                                Belum ada kategori.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $categories->links() }}
@endsection

