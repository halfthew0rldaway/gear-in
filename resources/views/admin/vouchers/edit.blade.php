@extends('layouts.admin')

@section('page-title', 'Edit Voucher')

@section('content')
    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid sm:grid-cols-2 gap-6">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Kode Voucher
                <input type="text" name="code" value="{{ old('code', $voucher->code) }}" required maxlength="50" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring font-mono uppercase">
                <p class="text-xs text-gray-500 mt-1">Akan otomatis diubah menjadi huruf besar</p>
            </label>
            
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Nama Voucher
                <input type="text" name="name" value="{{ old('name', $voucher->name) }}" required maxlength="255" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
        </div>
        
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Deskripsi
            <textarea name="description" rows="3" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">{{ old('description', $voucher->description) }}</textarea>
        </label>
        
        <div class="grid sm:grid-cols-2 gap-6">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Tipe Diskon
                <select name="type" id="voucher-type" required class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    <option value="percentage" {{ old('type', $voucher->type) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                    <option value="fixed" {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>Jumlah Tetap (Rp)</option>
                </select>
            </label>
            
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Nilai Diskon
                <input type="number" name="value" id="voucher-value" value="{{ old('value', $voucher->value) }}" required min="0" step="0.01" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                <p class="text-xs text-gray-500 mt-1" id="value-hint">Masukkan persentase (contoh: 10 untuk 10%)</p>
            </label>
        </div>
        
        <div id="max-discount-field" class="{{ old('type', $voucher->type) === 'percentage' ? '' : 'hidden' }}">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Maksimal Diskon (Rp)
                <input type="number" name="max_discount" value="{{ old('max_discount', $voucher->max_discount) }}" min="0" step="0.01" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                <p class="text-xs text-gray-500 mt-1">Batasi maksimal diskon untuk tipe persentase</p>
            </label>
        </div>
        
        <div class="grid sm:grid-cols-2 gap-6">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Minimum Pembelian (Rp)
                <input type="number" name="min_purchase" value="{{ old('min_purchase', $voucher->min_purchase) }}" min="0" step="0.01" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
            
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Batas Penggunaan per User
                <input type="number" name="user_limit" value="{{ old('user_limit', $voucher->user_limit) }}" required min="1" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
        </div>
        
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
            Batas Penggunaan Total
            <input type="number" name="usage_limit" value="{{ old('usage_limit', $voucher->usage_limit) }}" min="1" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            <p class="text-xs text-gray-500 mt-1">Kosongkan untuk tidak ada batas</p>
        </label>
        
        <div class="grid sm:grid-cols-2 gap-6">
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Mulai Berlaku
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $voucher->starts_at ? $voucher->starts_at->format('Y-m-d\TH:i') : '') }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
            
            <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">
                Berakhir
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d\TH:i') : '') }}" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:ring-gray-900 focus-ring">
            </label>
        </div>
        
        <label class="text-xs uppercase tracking-[0.4em] text-gray-400 inline-flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
            Aktif
        </label>
        
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.vouchers.index') }}" class="px-6 py-3 rounded-full border border-gray-300 text-gray-900 text-xs uppercase tracking-[0.4em] hover:bg-gray-100 transition focus-ring">Batal</a>
            <button type="submit" class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring">Perbarui</button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.getElementById('voucher-type').addEventListener('change', function() {
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

