@extends('layouts.admin')

@section('page-title', 'Tambah Kategori')

@section('content')
    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4">
        @csrf
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Nama
            <input type="text" name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
        </label>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Deskripsi
            <textarea name="description" rows="3" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">{{ old('description') }}</textarea>
        </label>
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 inline-flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
            Aktif
        </label>
        <div class="flex justify-end">
            <button class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">Simpan</button>
        </div>
    </form>
@endsection

