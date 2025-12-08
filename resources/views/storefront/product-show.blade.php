@extends('layouts.storefront')

@section('title', $product->name.' · gear-in')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
        <div class="grid lg:grid-cols-[1.2fr_1fr] gap-6 lg:gap-12 scroll-reveal">
            <!-- Left Column: Image and Details -->
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-[32px] p-5 sm:p-7 lg:p-8">
                    <div class="aspect-square rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden relative">
                        @php
                            $images = $product->images;
                            $hasImages = $images->count() > 0;
                            $isSoldOut = $product->stock == 0;
                        @endphp
                        
                        @if($isSoldOut)
                            <!-- Sold Out Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center z-10">
                                <div class="text-center">
                                    <p class="text-4xl font-bold text-white mb-2">SOLD OUT</p>
                                    <p class="text-sm text-gray-200">Stok habis</p>
                                </div>
                            </div>
                        @endif

                        @if($hasImages)
                            <!-- Image Carousel -->
                            <div class="product-carousel relative w-full h-full">
                                <div class="carousel-container relative w-full h-full overflow-hidden">
                                    @foreach($images as $index => $img)
                                        @php
                                            $imgUrl = \Illuminate\Support\Facades\Storage::url($img->image_path);
                @endphp
                                        <div class="carousel-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0 w-full h-full transition-opacity duration-500">
                                            <img src="{{ $imgUrl }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" class="w-full h-full object-cover" />
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($images->count() > 1)
                                    <!-- Navigation Arrows -->
                                    <button class="carousel-prev absolute left-2 top-1/2 -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 transition z-20" onclick="changeSlide(-1)">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button class="carousel-next absolute right-2 top-1/2 -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 transition z-20" onclick="changeSlide(1)">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Dots Indicator -->
                                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                                        @foreach($images as $index => $img)
                                            <button class="carousel-dot w-2 h-2 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white bg-opacity-50' }} transition" onclick="goToSlide({{ $index }})"></button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                @else
                            <!-- Default gear-in placeholder (black background) -->
                            <div class="w-full h-full bg-black flex items-center justify-center">
                                <span class="text-xs uppercase tracking-[0.3em] text-white">gear-in</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($product->description)
                    <div class="bg-white border border-gray-200 rounded-[32px] p-5 sm:p-7 lg:p-8">
                        <p class="text-xs uppercase tracking-[0.5em] text-gray-400 mb-5">Deskripsi</p>
                        <div class="description-content">
                            @php
                                // Split description by double newlines to create paragraphs
                                $paragraphs = preg_split('/\n\s*\n/', $product->description);
                            @endphp
                            <div class="space-y-4 text-sm sm:text-base lg:text-[15px] leading-[1.8] text-gray-600 max-w-none">
                                @foreach($paragraphs as $paragraph)
                                    @if(trim($paragraph))
                                        <p class="text-justify sm:text-left leading-[1.8]">{{ trim($paragraph) }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($product->specifications && count($product->specifications) > 0)
                    <div class="bg-white border border-gray-200 rounded-[32px] p-5 sm:p-7 lg:p-8 space-y-4">
                        <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Spesifikasi</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5">
                            @foreach($product->specifications as $key => $value)
                                <div class="flex flex-col sm:flex-row sm:items-start sm:items-center sm:justify-between gap-1 sm:gap-4 py-2.5 border-b border-gray-100 last:border-none">
                                    <p class="text-xs text-gray-500 uppercase tracking-[0.2em] font-medium sm:min-w-[140px] flex-shrink-0">{{ $key }}</p>
                                    <p class="text-sm font-semibold text-gray-900 sm:text-right">{{ $value }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Product Info and Actions -->
            <div class="space-y-6 sm:space-y-8">
                <div class="space-y-3 sm:space-y-4">
                    <p class="text-xs uppercase tracking-[0.5em] text-gray-400">{{ $product->category->name }}</p>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-semibold leading-tight">{{ $product->name }}</h1>
                    @if($product->summary)
                        <p class="text-sm sm:text-base text-gray-500 leading-relaxed">{{ $product->summary }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 pt-1">
                        <p class="text-xl sm:text-2xl font-semibold">{{ $product->formatted_price }}</p>
                        @if($product->approvedReviews->count() > 0)
                            <div class="flex items-center gap-2">
                                <span class="text-yellow-500 text-lg">★</span>
                                <span class="text-sm font-semibold">{{ number_format($product->average_rating, 1) }}</span>
                                <span class="text-xs text-gray-500">({{ $product->approvedReviews->count() }})</span>
        </div>
                        @endif
            </div>
            </div>

                <!-- Quick Info Box -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-4 sm:p-6 space-y-4">
                    <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Informasi Produk</p>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-none">
                            <p class="text-xs text-gray-500 uppercase tracking-[0.2em] font-medium">SKU</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $product->sku }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-none">
                            <p class="text-xs text-gray-500 uppercase tracking-[0.2em] font-medium">Status Stok</p>
                            <div class="flex items-center gap-2">
                                @if($product->stock > 0)
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    <p class="text-sm font-semibold text-green-600">Tersedia</p>
                                @else
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                    <p class="text-sm font-semibold text-red-600">Stok Habis</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-none">
                            <p class="text-xs text-gray-500 uppercase tracking-[0.2em] font-medium">Ketersediaan</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $product->stock }} unit</p>
                        </div>
                        @if($product->is_featured)
                        <div class="flex items-center justify-between py-2">
                            <p class="text-xs text-gray-500 uppercase tracking-[0.2em] font-medium">Produk</p>
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold uppercase tracking-[0.2em]">Unggulan</span>
                        </div>
                        @endif
                    </div>
                </div>

                @auth
                <div class="bg-white border border-gray-200 rounded-[32px] p-4 sm:p-6 space-y-4 sm:space-y-5">
                    <form action="{{ route('cart.store') }}" method="POST" class="space-y-4" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        @if($variants->count() > 0)
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2 block">Pilih Varian <span class="text-red-500">*</span></label>
                                <select name="variant_id" id="variantSelect" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring" required>
                                    <option value="">-- Pilih Varian --</option>
                                    @foreach($variants as $variant)
                                        <option value="{{ $variant->id }}" {{ $variant->stock == 0 ? 'disabled' : '' }}>
                                            {{ $variant->name }} 
                                            @if($variant->price_adjustment != 0)
                                                ({{ $variant->price_adjustment > 0 ? '+' : '' }}{{ 'Rp '.number_format($variant->price_adjustment, 0, ',', '.') }})
                                            @endif
                                            {{ $variant->stock == 0 ? '- Stok Habis' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500">Pilih varian sebelum menambahkan ke keranjang</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-[1fr_2fr] gap-3 sm:gap-4 items-end">
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">Jumlah</label>
                                <input type="number" min="1" max="{{ $product->stock }}" name="quantity" value="1" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-center text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                            </div>
                            <button type="submit" class="w-full px-4 sm:px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring">+ Keranjang</button>
                        </div>
                    </form>

                    <div class="pt-4 border-t border-gray-100 space-y-3">
                        <div class="flex flex-col sm:flex-row items-stretch gap-3">
                            @if($isInWishlist)
                                <form action="{{ route('wishlist.destroy', $product) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-3 rounded-full border border-red-600 text-red-600 text-xs uppercase tracking-[0.4em] hover:bg-red-600 hover:text-white transition">❤️ Daftar Keinginan</button>
                                </form>
                            @else
                                <form action="{{ route('wishlist.store', $product) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-3 rounded-full border border-gray-300 text-gray-600 text-xs uppercase tracking-[0.4em] hover:border-gray-900 hover:text-gray-900 transition">♡ Daftar Keinginan</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white border border-gray-200 rounded-[32px] p-4 sm:p-6 space-y-4 text-center">
                    <a href="{{ route('login') }}" class="inline-block px-6 py-3 rounded-full border border-gray-900 text-gray-900 text-xs uppercase tracking-[0.4em] hover:bg-gray-900 hover:text-white transition">Masuk untuk membeli</a>
                    <p class="text-xs text-gray-500">{{ $product->stock }} unit tersedia</p>
                </div>
                @endauth

                <!-- Shipping & Warranty Info -->
                <div class="bg-white border border-gray-200 rounded-[32px] p-4 sm:p-6 space-y-5">
                    <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Pengiriman & Garansi</p>
                    
                    <!-- Shipping Info -->
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center mt-0.5">
                                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 mb-1">Pengiriman</p>
                                <p class="text-xs text-gray-600 leading-relaxed">Pengiriman ke seluruh Indonesia. Estimasi 2-5 hari kerja untuk pengiriman standar, 1-2 hari untuk pengiriman ekspres.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center mt-0.5">
                                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 mb-1">Garansi</p>
                                <p class="text-xs text-gray-600 leading-relaxed">Garansi resmi 1 tahun untuk produk hardware. Garansi berlaku sesuai dengan ketentuan pabrikan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center mt-0.5">
                                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 mb-1">Kebijakan Retur</p>
                                <p class="text-xs text-gray-600 leading-relaxed">Retur dapat dilakukan dalam 7 hari setelah pembelian dengan kondisi produk masih segel dan tidak digunakan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16 space-y-6 sm:space-y-8">
        <div class="bg-white border border-gray-200 rounded-[32px] p-5 sm:p-7 lg:p-8 space-y-6 sm:space-y-8">
            <div>
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400 mb-2">Reviews</p>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-semibold">Ulasan Pelanggan</h2>
            </div>

            @auth
                @if($canReview)
                    <div class="border-t border-gray-100 pt-6 sm:pt-8 space-y-5 sm:space-y-6">
                        <div>
                            <p class="text-sm sm:text-base font-semibold text-gray-900 mb-1">Tulis Review</p>
                            <p class="text-xs text-gray-500">Bagikan pengalaman Anda tentang produk ini</p>
                        </div>
                        <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="space-y-2.5">
                                <label for="review-rating-{{ $product->id }}" class="text-xs uppercase tracking-[0.4em] text-gray-400 block">Rating <span class="text-red-500">*</span></label>
                                <select name="rating" id="review-rating-{{ $product->id }}" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-gray-900 focus:ring-gray-900" required>
                                    <option value="5">5 - Excellent</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="3">3 - Good</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="1">1 - Poor</option>
                                </select>
                            </div>
                            <div class="space-y-2.5">
                                <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">Komentar (Opsional)</label>
                                <textarea name="comment" rows="5" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-gray-900 focus:ring-gray-900 resize-none" placeholder="Bagikan pengalaman Anda tentang produk ini..."></textarea>
                            </div>
                            <button type="submit" class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">Submit Review</button>
                        </form>
                    </div>
                @endif
            @endauth

            <div class="border-t border-gray-100 pt-6 sm:pt-8 space-y-6 sm:space-y-7" data-stagger="100" data-stagger-selector="> div">
                @forelse ($reviews as $review)
                    <div class="space-y-4 pb-6 sm:pb-7 border-b border-gray-100 last:border-none last:pb-0 scroll-reveal">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm sm:text-base font-semibold text-gray-700">{{ substr($review->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-semibold text-gray-900">{{ $review->user->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $review->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-lg sm:text-xl {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed pl-0 sm:pl-14">{{ $review->comment }}</p>
                        @endif
                        @if($review->admin_reply)
                            <div class="bg-gray-50 rounded-2xl p-4 sm:p-5 space-y-2.5 ml-0 sm:ml-14">
                                <p class="text-xs text-gray-500 uppercase tracking-[0.2em] font-medium">Balasan Admin</p>
                                <p class="text-sm sm:text-base text-gray-700 leading-relaxed">{{ $review->admin_reply }}</p>
                                @if($review->adminRepliedBy)
                                    <p class="text-xs text-gray-500 mt-1">— {{ $review->adminRepliedBy->name }}, {{ $review->admin_replied_at->format('d M Y') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-8 sm:py-12">
                        <p class="text-sm sm:text-base text-gray-500">Belum ada review untuk produk ini.</p>
                    </div>
                    @endforelse
            </div>
        </div>
    </section>

    <!-- Related Products Section -->
    @if($relatedProducts->count() > 0)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16 scroll-reveal">
        <div class="space-y-6">
            <div>
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Produk Terkait</p>
                <h2 class="text-2xl sm:text-3xl font-semibold mt-2">Produk Lainnya</h2>
            </div>
            
            <div class="relative">
                <div class="overflow-hidden">
                    <div class="related-products-carousel flex gap-4 sm:gap-6 transition-transform duration-500 ease-in-out" id="relatedProductsCarousel">
                        @foreach($relatedProducts as $related)
                            <div class="related-product-slide flex-shrink-0 w-[280px] sm:w-[320px]">
                                <x-product.card :product="$related" />
                            </div>
                        @endforeach
                    </div>
                </div>
                
                @if($relatedProducts->count() > 3)
                    <!-- Navigation Buttons -->
                    <button class="related-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white border border-gray-300 rounded-full p-3 shadow-lg hover:bg-gray-50 transition z-10 opacity-0 pointer-events-none hidden sm:block" id="relatedPrevBtn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="related-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white border border-gray-300 rounded-full p-3 shadow-lg hover:bg-gray-50 transition z-10 hidden sm:block" id="relatedNextBtn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </section>
    @endif

    @push('scripts')
    <script>
        // Validate variant selection before form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('addToCartForm');
            const variantSelect = document.getElementById('variantSelect');
            
            if (form && variantSelect) {
                form.addEventListener('submit', async function(e) {
                    if (!variantSelect.value) {
                        e.preventDefault();
                        await window.customAlert('Silakan pilih varian terlebih dahulu.', 'Varian Belum Dipilih');
                        variantSelect.focus();
                        variantSelect.classList.add('border-red-500');
                        return false;
                    }
                });
                
                // Remove error styling when variant is selected
                variantSelect.addEventListener('change', function() {
                    this.classList.remove('border-red-500');
                });
            }

            // Image Carousel with debouncing
            let currentSlide = 0;
            let isTransitioning = false;
            let autoSlideInterval = null;
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.carousel-dot');
            const totalSlides = slides.length;

            // Initialize slides
            function initializeSlides() {
                slides.forEach((slide, i) => {
                    slide.style.opacity = i === 0 ? '1' : '0';
                    slide.style.pointerEvents = 'none';
                });
            }

            function showSlide(index) {
                // Prevent multiple transitions at once
                if (isTransitioning) return;
                
                isTransitioning = true;

                // Normalize index
                if (index >= totalSlides) {
                    currentSlide = 0;
                } else if (index < 0) {
                    currentSlide = totalSlides - 1;
                } else {
                    currentSlide = index;
                }

                // Update slides
                slides.forEach((slide, i) => {
                    if (i === currentSlide) {
                        slide.style.opacity = '1';
                        slide.style.zIndex = '10';
                    } else {
                        slide.style.opacity = '0';
                        slide.style.zIndex = '1';
                    }
                });

                // Update dots
                dots.forEach((dot, i) => {
                    if (i === currentSlide) {
                        dot.classList.remove('bg-opacity-50');
                        dot.classList.add('bg-white');
                    } else {
                        dot.classList.add('bg-opacity-50');
                        dot.classList.remove('bg-white');
                    }
                });

                // Reset transition flag after animation
                setTimeout(() => {
                    isTransitioning = false;
                }, 500); // Match CSS transition duration
            }

            // Debounced slide change function
            let changeSlideTimeout = null;
            window.changeSlide = function(direction) {
                if (changeSlideTimeout) {
                    clearTimeout(changeSlideTimeout);
                }
                
                changeSlideTimeout = setTimeout(() => {
                    if (!isTransitioning) {
                        resetAutoSlide();
                        showSlide(currentSlide + direction);
                    }
                }, 100); // 100ms debounce
            };

            window.goToSlide = function(index) {
                if (changeSlideTimeout) {
                    clearTimeout(changeSlideTimeout);
                }
                
                changeSlideTimeout = setTimeout(() => {
                    if (!isTransitioning && index >= 0 && index < totalSlides) {
                        resetAutoSlide();
                        showSlide(index);
                    }
                }, 100);
            };

            // Auto-slide function
            function startAutoSlide() {
                if (totalSlides <= 1) return;
                
                autoSlideInterval = setInterval(() => {
                    if (!isTransitioning) {
                        showSlide(currentSlide + 1);
                    }
                }, 10000);
            }

            function resetAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                }
                startAutoSlide();
            }

            // Initialize
            if (totalSlides > 0) {
                initializeSlides();
                startAutoSlide();
            }

            // Related Products Carousel
            const relatedCarousel = document.getElementById('relatedProductsCarousel');
            const relatedPrevBtn = document.getElementById('relatedPrevBtn');
            const relatedNextBtn = document.getElementById('relatedNextBtn');
            
            if (relatedCarousel) {
                let relatedCurrentIndex = 0;
                let relatedIsTransitioning = false;
                const relatedSlides = relatedCarousel.querySelectorAll('.related-product-slide');
                const totalRelatedSlides = relatedSlides.length;
                
                function getSlidesPerView() {
                    if (window.innerWidth >= 1024) return 4; // lg: 4 slides
                    if (window.innerWidth >= 640) return 3;  // sm: 3 slides
                    return 1; // mobile: 1 slide
                }

                function updateRelatedCarousel() {
                    if (relatedIsTransitioning || totalRelatedSlides === 0) return;
                    relatedIsTransitioning = true;

                    const slidesPerView = getSlidesPerView();
                    const maxIndex = Math.max(0, totalRelatedSlides - slidesPerView);
                    
                    // Clamp index
                    relatedCurrentIndex = Math.max(0, Math.min(relatedCurrentIndex, maxIndex));

                    // Calculate translateX
                    const slideWidth = relatedSlides[0]?.offsetWidth || 320;
                    const gap = window.innerWidth >= 640 ? 24 : 16; // gap-4 or gap-6
                    const translateX = -(relatedCurrentIndex * (slideWidth + gap));

                    relatedCarousel.style.transform = `translateX(${translateX}px)`;

                    // Update button visibility
                    if (relatedPrevBtn) {
                        const isAtStart = relatedCurrentIndex === 0;
                        relatedPrevBtn.classList.toggle('opacity-0', isAtStart);
                        relatedPrevBtn.classList.toggle('pointer-events-none', isAtStart);
                    }
                    if (relatedNextBtn) {
                        const isAtEnd = relatedCurrentIndex >= maxIndex;
                        relatedNextBtn.classList.toggle('opacity-0', isAtEnd);
                        relatedNextBtn.classList.toggle('pointer-events-none', isAtEnd);
                    }

                    setTimeout(() => {
                        relatedIsTransitioning = false;
                    }, 500);
                }

                if (relatedPrevBtn) {
                    relatedPrevBtn.addEventListener('click', function() {
                        if (relatedCurrentIndex > 0 && !relatedIsTransitioning) {
                            relatedCurrentIndex--;
                            updateRelatedCarousel();
                        }
                    });
                }

                if (relatedNextBtn) {
                    relatedNextBtn.addEventListener('click', function() {
                        const slidesPerView = getSlidesPerView();
                        const maxIndex = Math.max(0, totalRelatedSlides - slidesPerView);
                        if (relatedCurrentIndex < maxIndex && !relatedIsTransitioning) {
                            relatedCurrentIndex++;
                            updateRelatedCarousel();
                        }
                    });
                }

                // Handle window resize
                let resizeTimeout;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(() => {
                        const newSlidesPerView = getSlidesPerView();
                        const newMaxIndex = Math.max(0, totalRelatedSlides - newSlidesPerView);
                        if (relatedCurrentIndex > newMaxIndex) {
                            relatedCurrentIndex = newMaxIndex;
                        }
                        updateRelatedCarousel();
                    }, 250);
                });

                // Initial update
                updateRelatedCarousel();
            }
        });
    </script>
    @endpush
@endsection

