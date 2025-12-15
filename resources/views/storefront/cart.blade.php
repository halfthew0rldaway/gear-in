@extends('layouts.storefront')

@section('title', 'Keranjang · gear-in')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8">
        <div class="scroll-reveal">
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Keranjang</p>
            <h1 class="text-4xl font-semibold">Ringkasan barang</h1>
        </div>

        <form id="cartForm" action="{{ route('checkout.index') }}" method="GET">
            <div class="bg-white border border-gray-200 rounded-[32px] p-4 sm:p-6 space-y-4 sm:space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 w-4 h-4" checked>
                        <label for="selectAll" class="text-sm font-semibold text-gray-900 cursor-pointer">Pilih Semua</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" id="bulkDeleteBtn" class="text-xs uppercase tracking-[0.4em] text-red-500 hover:text-red-600 transition opacity-50 pointer-events-none focus-ring" disabled>
                            Hapus Terpilih
                        </button>
                    </div>
                </div>
                @forelse ($cartItems as $item)
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center border-b border-gray-100 pb-4 sm:pb-5 last:border-none last:pb-0">
                        <div class="sm:col-span-1 flex items-center justify-center sm:justify-start">
                            <input type="checkbox" value="{{ $item->id }}" class="item-checkbox rounded border-gray-300 text-gray-900 focus:ring-gray-900 w-4 h-4" checked>
                        </div>
                        <div class="sm:col-span-4">
                            <div class="flex items-start gap-2">
                                @if($item->product->hasActiveDiscount())
                                    <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded flex-shrink-0 mt-0.5">
                                        -{{ number_format($item->product->discount_percentage, 0) }}%
                                    </span>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $item->product->category->name }}</p>
                                    <h2 class="text-base sm:text-lg font-semibold mt-1">{{ $item->product->name }}</h2>
                                    @if($item->variant)
                                        <p class="text-sm text-gray-600 font-medium mt-1">{{ $item->variant->name }}</p>
                                    @endif
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1 line-clamp-2">{{ $item->product->summary }}</p>
                                    
                                    @php
                                        $stock = $item->variant ? $item->variant->stock : $item->product->stock;
                                    @endphp
                                    
                                    @if($stock == 0)
                                        <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-red-50 text-red-700 text-[10px] font-bold uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                            Stok Habis
                                        </div>
                                    @elseif($item->quantity > $stock)
                                        <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                            Sisa Stok: {{ $stock }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="sm:col-span-4 flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex items-center gap-2">
                                <label class="text-xs text-gray-500 sm:hidden">Jumlah:</label>
                                <input 
                                    type="number" 
                                    min="1" 
                                    max="{{ $item->variant ? $item->variant->stock : $item->product->stock }}" 
                                    value="{{ $item->quantity }}" 
                                    data-item-id="{{ $item->id }}"
                                    data-update-url="{{ route('cart.update', $item) }}"
                                    class="quantity-input w-20 rounded-full border border-gray-200 px-4 py-2 text-center text-sm focus:border-gray-900 focus:ring-gray-900 focus-ring h-10"
                                >
                            </div>
                        </div>
                        <div class="sm:col-span-3 flex items-center justify-between sm:justify-end">
                            <div class="text-right">
                                @php
                                    $basePrice = $item->product->price;
                                    if ($item->variant) {
                                        $basePrice += $item->variant->price_adjustment;
                                    }
                                    $finalPrice = $basePrice;
                                    if ($item->product->hasActiveDiscount()) {
                                        $discount = $basePrice * ($item->product->discount_percentage / 100);
                                        $finalPrice = $basePrice - $discount;
                                    }
                                    $totalPrice = $finalPrice * $item->quantity;
                                @endphp
                                @if($item->product->hasActiveDiscount() && $finalPrice < $basePrice)
                                    <p class="text-sm sm:text-base font-bold text-red-600 item-price">{{ 'Rp '.number_format($totalPrice, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-400 line-through">{{ 'Rp '.number_format($basePrice * $item->quantity, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-sm sm:text-base font-semibold item-price">{{ 'Rp '.number_format($totalPrice, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-8">Keranjang masih kosong.</p>
                @endforelse
            </div>

            <div class="grid sm:grid-cols-2 gap-6 mt-6">
                <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-2">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span id="selectedSubtotal">{{ 'Rp '.number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Pengiriman</span>
                        <span id="selectedShipping">{{ 'Rp '.number_format($shipping, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold border-t border-gray-100 pt-4">
                        <span>Total</span>
                        <span id="selectedTotal">{{ 'Rp '.number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-4">
                    <p class="text-sm text-gray-500">Lanjutkan ke checkout untuk memasukkan detail pengiriman dan membuat pesanan.</p>
                    <button type="submit" class="w-full px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring disabled:opacity-50 disabled:pointer-events-none" id="checkoutBtn" {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                        {{ $cartItems->isEmpty() ? 'Pilih Item untuk Checkout' : 'Checkout' }}
                    </button>
                    @if($cartItems->isNotEmpty())
                        <div class="progress-container mt-2">
                            <div class="progress-bar" style="width: 0%;"></div>
                        </div>
                    @endif
                </div>
            </div>
        </form>

        <form id="bulkDeleteForm" action="{{ route('cart.bulk-delete') }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
            <input type="hidden" name="items" id="bulkDeleteItems">
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const bulkDeleteItems = document.getElementById('bulkDeleteItems');
            const checkoutBtn = document.getElementById('checkoutBtn');
            const cartForm = document.getElementById('cartForm');

            // Item prices and quantities (stored in data attributes)
            const itemData = {};
            @foreach($cartItems as $item)
                @php
                    $basePrice = $item->product->price;
                    if ($item->variant) {
                        $basePrice += $item->variant->price_adjustment;
                    }
                    $finalPrice = $basePrice;
                    if ($item->product->hasActiveDiscount()) {
                        $discount = $basePrice * ($item->product->discount_percentage / 100);
                        $finalPrice = $basePrice - $discount;
                    }
                @endphp
                itemData[{{ $item->id }}] = {
                    unitPrice: {{ $finalPrice }},
                    quantity: {{ $item->quantity }},
                    maxStock: {{ $item->variant ? $item->variant->stock : $item->product->stock }}
                };
            @endforeach

            const shippingFee = 15000;

            // Update item prices when quantity changes
            function updateItemPrice(itemId, quantity) {
                if (itemData[itemId]) {
                    itemData[itemId].quantity = quantity;
                    // Update the displayed price for this item
                    const itemRow = document.querySelector(`input[data-item-id="${itemId}"]`)?.closest('.grid');
                    if (itemRow) {
                        const priceElement = itemRow.querySelector('.item-price');
                        if (priceElement) {
                            const total = itemData[itemId].unitPrice * quantity;
                            priceElement.textContent = formatCurrency(total);
                        }
                    }
                }
            }

            function updateSelectedTotal() {
                const selected = Array.from(itemCheckboxes).filter(cb => cb.checked);
                const selectedIds = selected.map(cb => parseInt(cb.value));
                
                let subtotal = 0;
                selectedIds.forEach(id => {
                    if (itemData[id]) {
                        subtotal += itemData[id].unitPrice * itemData[id].quantity;
                    }
                });

                const shipping = selected.length > 0 ? shippingFee : 0;
                const total = subtotal + shipping;

                document.getElementById('selectedSubtotal').textContent = formatCurrency(subtotal);
                document.getElementById('selectedShipping').textContent = formatCurrency(shipping);
                document.getElementById('selectedTotal').textContent = formatCurrency(total);

                // Enable/disable buttons
                if (selected.length > 0) {
                    bulkDeleteBtn.disabled = false;
                    bulkDeleteBtn.classList.remove('opacity-50', 'pointer-events-none');
                    checkoutBtn.textContent = `Checkout (${selected.length} Item)`;
                    checkoutBtn.disabled = false;
                    checkoutBtn.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    bulkDeleteBtn.disabled = true;
                    bulkDeleteBtn.classList.add('opacity-50', 'pointer-events-none');
                    checkoutBtn.textContent = 'Pilih Item untuk Checkout';
                    checkoutBtn.disabled = true;
                    checkoutBtn.classList.add('opacity-50', 'pointer-events-none');
                }
            }

            function formatCurrency(amount) {
                return 'Rp ' + amount.toLocaleString('id-ID');
            }

            // Select all functionality
            selectAll?.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectedTotal();
            });

            // Individual checkbox change
            itemCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    // Update select all state
                    const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
                    selectAll.checked = allChecked;
                    selectAll.indeterminate = someChecked && !allChecked;
                    
                    updateSelectedTotal();
                });
            });

            // Bulk delete
            bulkDeleteBtn?.addEventListener('click', async function() {
                const selected = Array.from(itemCheckboxes).filter(cb => cb.checked);
                if (selected.length === 0) return;

                const confirmed = await window.customConfirm(
                    `Hapus ${selected.length} item dari keranjang?`,
                    'Hapus Item'
                );
                
                if (confirmed) {
                    const selectedIds = selected.map(cb => cb.value);
                    bulkDeleteItems.value = JSON.stringify(selectedIds);
                    bulkDeleteForm.submit();
                }
            });

            // Checkout only selected items
            cartForm?.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const selected = Array.from(itemCheckboxes).filter(cb => cb.checked);
                if (selected.length === 0) {
                    await window.customAlert('Pilih minimal 1 item untuk checkout.', 'Item Belum Dipilih');
                    return false;
                }

                // Remove any existing selected_items inputs to avoid duplicates
                const existingInputs = cartForm.querySelectorAll('input[name="selected_items[]"]');
                existingInputs.forEach(input => input.remove());

                // Add selected items as hidden inputs
                selected.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_items[]';
                    input.value = cb.value;
                    cartForm.appendChild(input);
                });

                // Submit form
                this.submit();
            });

            // Auto-update quantity on change (with debounce)
            let updateTimeout;
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    const itemId = parseInt(this.dataset.itemId);
                    const newQuantity = parseInt(this.value) || 1;
                    const maxStock = parseInt(this.max) || 1;
                    const finalQuantity = Math.min(Math.max(1, newQuantity), maxStock);
                    
                    // Update input value if it was out of bounds
                    if (finalQuantity !== newQuantity) {
                        this.value = finalQuantity;
                    }

                    // Update local data
                    if (itemData[itemId]) {
                        itemData[itemId].quantity = finalQuantity;
                        updateItemPrice(itemId, finalQuantity);
                        updateSelectedTotal();
                    }

                    // Debounce API call
                    clearTimeout(updateTimeout);
                    updateTimeout = setTimeout(() => {
                        const updateUrl = this.dataset.updateUrl;
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                        const formData = new FormData();
                        formData.append('quantity', finalQuantity);
                        formData.append('_token', csrfToken);
                        formData.append('_method', 'PATCH');

                        fetch(updateUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken,
                            }
                        }).then(response => {
                            if (response.ok) {
                                return response.json();
                            }
                            throw new Error('Update failed');
                        }).then(data => {
                            // Success - update totals
                            updateSelectedTotal();
                        }).catch(error => {
                            console.error('Error updating quantity:', error);
                            // Reload page to sync state if error
                            window.location.reload();
                        });
                    }, 800);
                });

                input.addEventListener('blur', function() {
                    // Ensure value is valid on blur
                    const itemId = parseInt(this.dataset.itemId);
                    const newQuantity = parseInt(this.value) || 1;
                    const maxStock = parseInt(this.max) || 1;
                    const finalQuantity = Math.min(Math.max(1, newQuantity), maxStock);
                    
                    if (this.value != finalQuantity) {
                        this.value = finalQuantity;
                        // Trigger change event
                        this.dispatchEvent(new Event('change'));
                    }
                });
            });

            // Initial update
            updateSelectedTotal();

            // Progress bar animation saat checkout
            const progressBar = document.querySelector('.progress-bar');
            if (checkoutBtn && progressBar) {
                checkoutBtn.addEventListener('click', function() {
                    if (window.updateProgressBar) {
                        // Simulate progress
                        let progress = 0;
                        const interval = setInterval(() => {
                            progress += 10;
                            if (progress <= 100) {
                                progressBar.style.width = `${progress}%`;
                            } else {
                                clearInterval(interval);
                            }
                        }, 50);
                    }
                });
            }
        });
    </script>
@endsection

