@extends('layouts.admin')

@section('page-title', 'Voucher')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Voucher</h1>
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.vouchers.index') }}" method="GET" class="relative">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari voucher..." class="w-64 rounded-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm focus-ring">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-900 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
            <a href="{{ route('admin.vouchers.create') }}" class="text-xs uppercase tracking-[0.4em] border border-gray-900 px-4 py-2 rounded-full hover:bg-gray-900 hover:text-white transition btn-ripple focus-ring">Tambah Voucher</a>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-[32px] overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-[0.4em] text-gray-400">
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Tipe</th>
                    <th class="px-6 py-4">Nilai</th>
                    <th class="px-6 py-4">Total Diskon</th>
                    <th class="px-6 py-4">Digunakan</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($vouchers as $voucher)
                    <tr class="scroll-reveal">
                        <td class="px-6 py-4">
                            <p class="font-semibold font-mono">{{ $voucher->code }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $voucher->name }}</p>
                            @if($voucher->description)
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($voucher->description, 50) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs uppercase tracking-[0.2em] text-gray-600">
                                {{ $voucher->type === 'percentage' ? 'Persentase' : 'Tetap' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($voucher->type === 'percentage')
                                <span class="font-semibold">{{ number_format($voucher->value, 0) }}%</span>
                                @if($voucher->max_discount)
                                    <p class="text-xs text-gray-500">Max: Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</p>
                                @endif
                            @else
                                <span class="font-semibold">Rp {{ number_format($voucher->value, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">
                                Rp {{ number_format($voucher->usages_sum_discount_amount ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500">Total Potongan</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm">
                                {{ $voucher->usages_count }} / {{ $voucher->usage_limit ?? '∞' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $isExpired = $voucher->expires_at && now() > $voucher->expires_at;
                                $isNotStarted = $voucher->starts_at && now() < $voucher->starts_at;
                            @endphp
                            @if($isExpired)
                                <span class="text-xs uppercase tracking-[0.4em] text-gray-400">Kedaluwarsa</span>
                            @elseif($isNotStarted)
                                <span class="text-xs uppercase tracking-[0.4em] text-yellow-600">Belum Dimulai</span>
                            @elseif($voucher->is_active)
                                <span class="text-xs uppercase tracking-[0.4em] text-green-600">Aktif</span>
                            @else
                                <span class="text-xs uppercase tracking-[0.4em] text-gray-400">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900 link-underline focus-ring">Edit</a>
                            <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="inline-block ml-4">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs uppercase tracking-[0.4em] text-red-500 hover:text-red-600 focus-ring">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                            @if(request('search'))
                                Tidak ada voucher yang ditemukan untuk "{{ request('search') }}".
                            @else
                                Belum ada voucher.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $vouchers->links() }}
@endsection

