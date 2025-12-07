@extends('layouts.storefront')

@section('title', $product->name.' · gear-in')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
        <div class="grid lg:grid-cols-[1.2fr_1fr] gap-6 lg:gap-12">
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
                @auth
                <div class="bg-white border border-gray-200 rounded-[32px] p-4 sm:p-6 space-y-4 sm:space-y-5">
                    <form action="{{ route('cart.store') }}" method="POST" class="space-y-4" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        @if($variants->count() > 0)
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2 block">Pilih Varian <span class="text-red-500">*</span></label>
                                <select name="variant_id" id="variantSelect" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900" required>
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
                                <input type="number" min="1" max="{{ $product->stock }}" name="quantity" value="1" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-center text-gray-900 focus:border-gray-900 focus:ring-gray-900">
                            </div>
                            <button type="submit" class="w-full px-4 sm:px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">+ Keranjang</button>
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
                        <p class="text-xs text-gray-500 text-center">{{ $product->stock }} unit tersedia</p>
                    </div>
                </div>
                @else
                <div class="bg-white border border-gray-200 rounded-[32px] p-4 sm:p-6 space-y-4 text-center">
                    <a href="{{ route('login') }}" class="inline-block px-6 py-3 rounded-full border border-gray-900 text-gray-900 text-xs uppercase tracking-[0.4em] hover:bg-gray-900 hover:text-white transition">Masuk untuk membeli</a>
                    <p class="text-xs text-gray-500">{{ $product->stock }} unit tersedia</p>
                </div>
                @endauth
                
                <div class="space-y-4">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Produk terkait</p>
                    <div class="grid gap-4 sm:grid-cols-1">
                    @forelse ($relatedProducts as $related)
                            <a href="{{ route('products.show', $related) }}" class="p-4 sm:p-5 border border-gray-200 rounded-2xl hover:border-gray-900 transition space-y-2">
                            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $related->category->name }}</p>
                                <p class="text-sm sm:text-base font-semibold">{{ $related->name }}</p>
                                <p class="text-xs sm:text-sm text-gray-500">{{ $related->formatted_price }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500">Tidak ada produk terkait.</p>
                    @endforelse
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
                                <label class="text-xs uppercase tracking-[0.4em] text-gray-400 block">Rating <span class="text-red-500">*</span></label>
                                <select name="rating" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-gray-900 focus:ring-gray-900" required>
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

            <div class="border-t border-gray-100 pt-6 sm:pt-8 space-y-6 sm:space-y-7">
                @forelse ($reviews as $review)
                    <div class="space-y-4 pb-6 sm:pb-7 border-b border-gray-100 last:border-none last:pb-0">
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

    @push('scripts')
    <script>
        // Validate variant selection before form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('addToCartForm');
            const variantSelect = document.getElementById('variantSelect');
            
            if (form && variantSelect) {
                form.addEventListener('submit', function(e) {
                    if (!variantSelect.value) {
                        e.preventDefault();
                        alert('Silakan pilih varian terlebih dahulu.');
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
        });
    </script>
    @endpush
@endsection

