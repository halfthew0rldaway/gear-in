@extends('layouts.admin')

@section('page-title', 'Tambah Voucher')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-1">Manajemen Voucher</p>
            <h1 class="text-3xl font-semibold">Tambah Voucher</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.vouchers.index') }}"
                class="px-5 py-2.5 rounded-full border border-gray-300 text-gray-600 text-xs uppercase tracking-[0.2em] hover:bg-gray-50 transition">
                Kembali
            </a>
            <button type="submit" form="voucher-form"
                class="px-5 py-2.5 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.2em] hover:bg-black transition btn-ripple">
                Simpan Voucher
            </button>
        </div>
    </div>

    <form action="{{ route('admin.vouchers.store') }}" method="POST" id="voucher-form">
        @csrf

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content (2 cols) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Basic Info -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-8">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Informasi Utama</h2>
                    <div class="space-y-6">
                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Kode
                                Voucher</span>
                            <input type="text" name="code" value="{{ old('code') }}" required maxlength="50"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 font-mono uppercase">
                            <p class="text-xs text-gray-500 mt-1">Akan otomatis diubah menjadi huruf besar</p>
                        </label>

                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Nama
                                Voucher</span>
                            <input type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                        </label>

                        <label class="block">
                            <span
                                class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Deskripsi</span>
                            <textarea name="description" rows="3"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">{{ old('description') }}</textarea>
                        </label>
                    </div>
                </div>

                <!-- Discount Rules -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-8">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Aturan Diskon</h2>
                    <div class="space-y-6">
                        <div class="grid sm:grid-cols-2 gap-6">
                            <label class="block">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Tipe
                                    Diskon</span>
                                <select name="type" id="voucher-type" required
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Persentase
                                        (%)</option>
                                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Jumlah Tetap (Rp)
                                    </option>
                                </select>
                            </label>

                            <label class="block">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Nilai
                                    Diskon</span>
                                <input type="number" name="value" id="voucher-value" value="{{ old('value') }}" required
                                    min="0" step="0.01"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                                <p class="text-xs text-gray-500 mt-1" id="value-hint">Masukkan persentase</p>
                            </label>
                        </div>

                        <div id="max-discount-field" class="hidden">
                            <label class="block">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Maksimal
                                    Diskon (Rp)</span>
                                <input type="number" name="max_discount" value="{{ old('max_discount') }}" min="0"
                                    step="0.01"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                                <p class="text-xs text-gray-500 mt-1">Batasi maksimal diskon untuk tipe persentase</p>
                            </label>
                        </div>

                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Minimum
                                Pembelian (Rp)</span>
                            <input type="number" name="min_purchase" value="{{ old('min_purchase', 0) }}" min="0"
                                step="0.01"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Sidebar (1 col) -->
            <div class="space-y-8">
                <!-- Status Card -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Status</h2>
                    <label
                        class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer transition">
                        <span class="text-sm font-medium text-gray-700">Aktif</span>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900">
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Limits -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Batasan</h2>
                    <div class="space-y-5">
                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Limit per
                                User</span>
                            <input type="number" name="user_limit" value="{{ old('user_limit', 1) }}" required min="1"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                        </label>

                        <label class="block">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-2 block">Limit Total
                                Penggunaan</span>
                            <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan untuk tanpa batas</p>
                        </label>
                    </div>
                </div>

                <!-- Period -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                    <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Periode</h2>
                    <div class="space-y-5">
                        <label class="block">
                            <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-2 block">Mulai
                                Berlaku</span>
                            <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}"
                                class="w-full rounded-xl border border-gray-200 px-2 py-2 text-xs focus:border-gray-900 focus:ring-gray-900">
                        </label>
                        <label class="block">
                            <span
                                class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-2 block">Berakhir</span>
                            <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                                class="w-full rounded-xl border border-gray-200 px-2 py-2 text-xs focus:border-gray-900 focus:ring-gray-900">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            document.getElementById('voucher-type').addEventListener('change', function () {
                const type = this.value;
                const valueInput = document.getElementById('voucher-value');
                const valueHint = document.getElementById('value-hint');
                const maxDiscountField = document.getElementById('max-discount-field');

                if (type === 'percentage') {
                    valueHint.textContent = 'Masukkan persentase (contoh: 10 untuk 10%)';
                    valueInput.setAttribute('max', '100');
                    maxDiscountField.classList.remove('hidden');
                } else {
                    valueHint.textContent = 'Masukkan jumlah tetap dalam Rupiah (contoh: 50000)';
                    valueInput.removeAttribute('max');
                    maxDiscountField.classList.add('hidden');
                }
            });

            // Trigger on load
            document.getElementById('voucher-type').dispatchEvent(new Event('change'));
        </script>
    @endpush
@endsection