@auth
    @if(auth()->user()->isCustomer())
        @php
            $promoProducts = \App\Models\Product::where('is_active', true)
                ->where('discount_percentage', '>', 0)
                ->where(function($query) {
                    $query->whereNull('discount_expires_at')
                          ->orWhere('discount_expires_at', '>=', now());
                })
                ->where(function($query) {
                    $query->whereNull('discount_starts_at')
                          ->orWhere('discount_starts_at', '<=', now());
                })
                ->with(['category', 'images'])
                ->latest()
                ->take(3)
                ->get();
            
            $isClosed = session('promo_widget_closed', false);
            $isMinimized = session('promo_widget_minimized', false);
        @endphp

        @if($promoProducts->isNotEmpty())
            @if(!$isClosed && !$isMinimized)
                <!-- Center Promo Modal (Like Ecommerce) -->
                <div id="promo-widget-overlay" class="fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4 promo-widget-overlay-container backdrop-blur-sm">
                    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg promo-widget-modal-content relative" onclick="event.stopPropagation();">
                        <!-- Close Button (Top Right) -->
                        <button id="promo-widget-close" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors focus-ring" aria-label="Tutup">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-gray-900 to-black px-6 py-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                    <h2 class="text-lg font-bold text-white uppercase tracking-[0.2em]">Promo Spesial</h2>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Products List -->
                        <div class="py-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            @foreach($promoProducts as $index => $product)
                                <a href="{{ route('products.show', $product) }}" class="block promo-product-link group promo-product-item">
                                    <div class="px-6 py-4 hover:bg-gray-50 transition-all duration-200 border-b border-gray-100 last:border-b-0 group-hover:shadow-sm">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <p class="text-xs uppercase tracking-[0.1em] text-gray-400">{{ $product->category->name }}</p>
                                                    @if($product->discount_percentage > 0)
                                                        <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded promo-discount-badge">
                                                            -{{ number_format($product->discount_percentage, 0) }}%
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm font-semibold text-gray-900 mb-1.5 truncate group-hover:text-gray-700">{{ $product->name }}</p>
                                                <div class="flex items-center gap-2">
                                                    @php
                                                        $originalPrice = $product->price;
                                                        $discount = $originalPrice * ($product->discount_percentage / 100);
                                                        $finalPrice = $originalPrice - $discount;
                                                    @endphp
                                                    <span class="text-base font-bold text-red-600">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($originalPrice, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            
            @if($isMinimized && !$isClosed)
                <!-- Minimized Promo Widget (Floating Button) - Di samping chat -->
                <div id="promo-widget-mini" class="fixed bottom-6 right-28 z-[9998]" style="z-index: 9998;">
                    <button 
                        id="promo-widget-mini-toggle" 
                        class="relative w-16 h-16 rounded-full bg-gradient-to-br from-red-500 to-red-600 text-white shadow-lg hover:shadow-2xl transition-all duration-300 flex items-center justify-center group animate-float hover:scale-110"
                        aria-label="Buka Promo"
                    >
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-white text-red-500 text-[10px] font-bold rounded-full flex items-center justify-center shadow-md">
                            {{ $promoProducts->count() }}
                        </span>
                        
                        <span class="absolute right-full mr-3 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            Lihat Promo
                        </span>
                    </button>
                </div>
            @endif
        @endif

        @if($promoProducts->isNotEmpty())
            <style>
                /* Fancy modal animations with performance optimization */
                .promo-widget-overlay-container {
                    animation: modalFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                    will-change: opacity;
                }
                
                .promo-widget-modal-content {
                    animation: modalSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
                    will-change: transform, opacity;
                }
                
                @keyframes modalFadeIn {
                    from {
                        opacity: 0;
                        backdrop-filter: blur(0px);
                    }
                    to {
                        opacity: 1;
                        backdrop-filter: blur(4px);
                    }
                }
                
                @keyframes modalSlideUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px) scale(0.95);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }
                
                .promo-widget-overlay-container.closing {
                    animation: modalFadeOut 0.25s cubic-bezier(0.4, 0, 1, 1) forwards;
                }
                
                .promo-widget-overlay-container.closing .promo-widget-modal-content {
                    animation: modalSlideDown 0.25s cubic-bezier(0.4, 0, 1, 1) forwards;
                }
                
                @keyframes modalFadeOut {
                    to {
                        opacity: 0;
                        backdrop-filter: blur(0px);
                    }
                }
                
                @keyframes modalSlideDown {
                    to {
                        opacity: 0;
                        transform: translateY(20px) scale(0.95);
                    }
                }
                
                /* Stagger animation for product items */
                .promo-product-item {
                    animation: itemSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) backwards;
                    will-change: transform, opacity;
                }
                
                .promo-product-item:nth-child(1) { animation-delay: 0.1s; }
                .promo-product-item:nth-child(2) { animation-delay: 0.15s; }
                .promo-product-item:nth-child(3) { animation-delay: 0.2s; }
                
                @keyframes itemSlideIn {
                    from {
                        opacity: 0;
                        transform: translateX(-10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
                
                /* Pulse animation for discount badge */
                @keyframes pulse-glow {
                    0%, 100% {
                        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
                    }
                    50% {
                        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0);
                    }
                }
                
                .promo-discount-badge {
                    animation: pulse-glow 2s ease-in-out infinite;
                }
                
                .custom-scrollbar::-webkit-scrollbar {
                    width: 4px;
                }
                
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: transparent;
                }
                
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #d1d5db;
                    border-radius: 2px;
                }
                
                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #9ca3af;
                }
                
                /* Adjust position when chat window is open */
                #chat-widget-window:not(.hidden) ~ #promo-widget-modal,
                #chat-widget-window:not(.hidden) ~ #promo-widget-mini {
                    bottom: calc(600px + 1.5rem);
                }
                
                @media (max-width: 768px) {
                    #promo-widget-modal {
                        right: 1rem;
                        bottom: 5.5rem;
                        width: calc(100vw - 2rem);
                        max-width: calc(100vw - 2rem);
                    }
                    
                    #promo-widget-mini {
                        right: 1rem;
                        bottom: 5.5rem;
                    }
                    
                    /* On mobile, stack widgets vertically when chat is open */
                    #chat-widget-window:not(.hidden) ~ #promo-widget-modal,
                    #chat-widget-window:not(.hidden) ~ #promo-widget-mini {
                        bottom: calc(100vh - 100px);
                    }
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const overlay = document.getElementById('promo-widget-overlay');
                    const closeBtn = document.getElementById('promo-widget-close');
                    const miniWidget = document.getElementById('promo-widget-mini');
                    const miniToggle = document.getElementById('promo-widget-mini-toggle');
                    
                    // Ensure overlay is visible with animation
                    if (overlay) {
                        overlay.classList.remove('hidden', 'closing');
                        overlay.style.display = 'flex';
                        overlay.style.opacity = '1';
                        overlay.style.visibility = 'visible';
                    }
                    
                    // Close modal when product is clicked
                    const productLinks = document.querySelectorAll('.promo-product-link');
                    productLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            // Prevent default navigation temporarily
                            e.preventDefault();
                            
                            const href = this.getAttribute('href');
                            
                            // Close modal immediately
                            if (overlay) {
                                overlay.classList.add('closing');
                                overlay.style.opacity = '0';
                                overlay.style.pointerEvents = 'none';
                            }
                            
                            // Minimize widget so it becomes floating button
                            fetch('{{ route('promo-widget.minimize') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({minimize: true})
                            }).then(() => {
                                // Navigate after minimizing
                                window.location.href = href;
                            }).catch(err => {
                                console.error('Error minimizing widget:', err);
                                // Navigate anyway
                                window.location.href = href;
                            });
                        });
                    });
                    
                    // Close on overlay click
                    if (overlay) {
                        overlay.addEventListener('click', function(e) {
                            if (e.target === overlay) {
                                closeModal();
                            }
                        });
                    }
                    
                    // Close on ESC key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && overlay && !overlay.classList.contains('closing')) {
                            closeModal();
                        }
                    });
                    
                    function closeModal() {
                        if (overlay) {
                            overlay.classList.add('closing');
                            setTimeout(() => {
                                fetch('{{ route('promo-widget.minimize') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({minimize: true})
                                }).then(() => {
                                    location.reload();
                                }).catch(err => {
                                    console.error('Error minimizing widget:', err);
                                    location.reload();
                                });
                            }, 200);
                        }
                    }
                    
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            closeModal();
                        });
                    }
                    
                    if (miniToggle) {
                        miniToggle.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            fetch('{{ route('promo-widget.minimize') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({minimize: false})
                            }).then(() => {
                                location.reload();
                            }).catch(err => {
                                console.error('Error opening widget:', err);
                                location.reload();
                            });
                        });
                    }
                });
            </script>
        @endif
    @endif
@endauth

