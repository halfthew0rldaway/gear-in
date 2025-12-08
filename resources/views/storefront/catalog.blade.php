@extends('layouts.storefront')

@section('title', 'Katalog Produk · gear-in')

@section('content')
    @php
        $queryParams = request()->only(['q', 'category']);
    @endphp
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">
        <header class="space-y-4">
            <p class="text-xs uppercase tracking-[0.5em] text-gray-500">Katalog</p>
                <div>
                    <h1 class="text-4xl font-semibold">Katalog Produk gear-in</h1>
                    <p class="text-sm text-gray-600 mt-2 max-w-2xl">
                        Filter dan cari perangkat gaming minimalis favoritmu. Semua produk tersusun rapi dengan stok real-time.
                    </p>
                </div>
        </header>

        <form action="{{ route('catalog') }}" method="GET" class="space-y-4">
            <div class="bg-white border border-gray-200 rounded-3xl p-5 space-y-5">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <p class="text-sm text-gray-600">Menampilkan {{ $products->total() }} produk</p>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <span class="text-xs uppercase tracking-[0.4em] text-gray-500">Urutkan:</span>
                            <select name="sort" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm">
                                <option value="newest" {{ ($sortBy ?? 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low" {{ ($sortBy ?? '') === 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="price_high" {{ ($sortBy ?? '') === 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                <option value="name_asc" {{ ($sortBy ?? '') === 'name_asc' ? 'selected' : '' }}>Nama: A-Z</option>
                                <option value="name_desc" {{ ($sortBy ?? '') === 'name_desc' ? 'selected' : '' }}>Nama: Z-A</option>
                                <option value="rating" {{ ($sortBy ?? '') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                                <option value="popular" {{ ($sortBy ?? '') === 'popular' ? 'selected' : '' }}>Paling Populer</option>
                            </select>
                        </label>
                        <a href="{{ route('catalog') }}" class="text-xs uppercase tracking-[0.5em] text-gray-500 hover:text-gray-900">Atur ulang</a>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-5 space-y-4">
                    <div class="relative">
                        <div class="flex items-center gap-3 border border-gray-300 rounded-full px-4 py-2 bg-white focus-within:border-gray-900 focus-within:ring-1 focus-within:ring-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="11" cy="11" r="7" />
                            <line x1="16.65" y1="16.65" x2="21" y2="21" />
                        </svg>
                        <input
                            type="search"
                            name="q"
                            value="{{ old('q', $searchQuery) }}"
                            placeholder="Cari keyboard, headset, game..."
                            class="w-full border-none bg-transparent focus:ring-0 text-sm text-gray-900 placeholder:text-gray-500"
                            />
                            </div>
            </div>

            @php
                $queryString = fn ($overrides = []) => array_filter(
                    array_merge(
                                ['q' => $searchQuery, 'sort' => $sortBy ?? 'newest'],
                        $overrides
                    ),
                    fn ($value) => filled($value)
                );
            @endphp

            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('catalog', $queryString(['category' => null])) }}"
                    class="px-4 py-2 rounded-full text-xs uppercase tracking-[0.3em] {{ $selectedCategory ? 'border border-gray-300 text-gray-600 hover:text-gray-900 hover:border-gray-900' : 'bg-gray-900 text-white' }}"
                >
                    Semua
                </a>
                @foreach ($categories as $category)
                    @php
                        $isActive = $selectedCategory && $selectedCategory->id === $category->id;
                    @endphp
                    <a
                        href="{{ route('catalog', $queryString(['category' => $category->slug])) }}"
                        class="px-4 py-2 rounded-full text-xs uppercase tracking-[0.3em] {{ $isActive ? 'bg-gray-900 text-white' : 'border border-gray-300 text-gray-600 hover:border-gray-900 hover:text-gray-900' }}"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 pt-4 border-t border-gray-200">
                @if ($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                @endif
                <label class="space-y-1">
                            <span class="text-xs uppercase tracking-[0.4em] text-gray-500">Harga Min</span>
                    <input type="number" name="min_price" value="{{ $filters['min_price'] }}" class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="0">
                </label>
                <label class="space-y-1">
                            <span class="text-xs uppercase tracking-[0.4em] text-gray-500">Harga Max</span>
                    <input type="number" name="max_price" value="{{ $filters['max_price'] }}" class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="5000000">
                </label>
                <label class="flex items-center gap-2 text-xs uppercase tracking-[0.4em] text-gray-500">
                    <input type="checkbox" name="in_stock" value="1" {{ $filters['in_stock'] ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            Stok Tersedia
                </label>
                <label class="flex items-center gap-2 text-xs uppercase tracking-[0.4em] text-gray-500">
                    <input type="checkbox" name="featured" value="1" {{ $filters['featured'] ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            Unggulan
                </label>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="px-5 py-2 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em]">Terapkan Filter</button>
                        <a href="{{ route('catalog') }}" class="px-5 py-2 rounded-full border border-gray-300 text-gray-600 text-xs uppercase tracking-[0.4em] hover:border-gray-900 hover:text-gray-900">Atur Ulang</a>
                    </div>
                </div>
                </div>
            </form>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" id="productGrid">
            @forelse ($products as $product)
                <div class="scroll-reveal">
                    <x-product.card :product="$product" />
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 bg-white border border-gray-200 rounded-3xl p-8 text-center">
                    <p class="text-lg font-semibold text-gray-900">Produk tidak ditemukan</p>
                    <p class="text-sm text-gray-600 mt-2">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                </div>
            @endforelse
        </div>

        <div>
            {{ $products->links() }}
        </div>
    </section>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Stagger animation for product cards
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach((card, index) => {
            card.style.setProperty('--index', index);
            if (index > 8) {
                card.style.animationDelay = `${(index % 9) * 0.05}s`;
            }
        });
        
        @auth
        // Handle quick add to cart for all product cards
        document.querySelectorAll('.quick-add-cart-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Check if variant form and variant is selected
                if (form.classList.contains('variant-form')) {
                    const variantSelect = form.querySelector('select[name="variant_id"]');
                    if (!variantSelect || !variantSelect.value) {
                        window.customAlert('Silakan pilih varian terlebih dahulu.', 'Varian Belum Dipilih');
                        variantSelect?.focus();
                        return;
                    }
                }
                
                const button = form.querySelector('button[type="submit"]');
                const textSpan = button.querySelector('.quick-add-text');
                const originalText = textSpan ? textSpan.textContent : 'Keranjang';
                const formData = new FormData(form);
                
                // Disable button and show loading
                button.disabled = true;
                if (textSpan) {
                    textSpan.textContent = 'Menambah...';
                }
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': formData.get('_token')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        if (textSpan) {
                            textSpan.textContent = 'Ditambahkan!';
                        }
                        button.classList.remove('bg-gray-900', 'hover:bg-black');
                        button.classList.add('bg-green-600', 'hover:bg-green-700');
                        
                        // Reset variant select if exists
                        const variantSelect = form.querySelector('select[name="variant_id"]');
                        if (variantSelect) {
                            variantSelect.value = '';
                        }
                        
                        // Reset after 2 seconds
                        setTimeout(() => {
                            if (textSpan) {
                                textSpan.textContent = originalText;
                            }
                            button.classList.remove('bg-green-600', 'hover:bg-green-700');
                            button.classList.add('bg-gray-900', 'hover:bg-black');
                            button.disabled = false;
                        }, 2000);
                    } else {
                        // Show error - if requires variant, redirect to product page
                        if (data.requires_variant) {
                            const productId = form.querySelector('input[name="product_id"]').value;
                            // Find the product link from the card
                            const productCard = form.closest('.group');
                            const productLink = productCard ? productCard.querySelector('a[href*="/products/"]') : null;
                            if (productLink) {
                                window.location.href = productLink.href;
                            } else {
                                window.location.href = '/products/' + productId;
                            }
                        } else {
                            window.customAlert(data.message || 'Gagal menambahkan ke keranjang.', 'Gagal Menambahkan');
                            button.disabled = false;
                            if (textSpan) {
                                textSpan.textContent = originalText;
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.customAlert('Terjadi kesalahan saat menambahkan ke keranjang.', 'Terjadi Kesalahan');
                    button.disabled = false;
                    if (textSpan) {
                        textSpan.textContent = originalText;
                    }
                });
            });
        });
        @endauth
    });
    </script>
    @endpush
@endsection


