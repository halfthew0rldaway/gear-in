@extends('layouts.storefront')

@section('title', 'Katalog Produk · gear-in')

@section('content')
    @php
        $queryParams = request()->only(['q', 'category']);
    @endphp
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">
        <header class="space-y-4">
            <p class="text-xs uppercase tracking-[0.5em] text-gray-500">Catalog</p>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-semibold">Katalog Produk gear-in</h1>
                    <p class="text-sm text-gray-600 mt-2 max-w-2xl">
                        Filter dan cari perangkat gaming minimalis favoritmu. Semua produk tersusun rapi dengan stok real-time.
                    </p>
                </div>
                <form action="{{ route('catalog') }}" method="GET" class="w-full lg:max-w-md" x-data="catalogSearch('{{ route('catalog.search') }}', @js($queryParams))">
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
                            x-model="query"
                            @input.debounce.300ms="fetchResults"
                        />
                        @if ($selectedCategory)
                            <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                        @endif
                        <template x-if="results.length">
                            <div class="absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-2xl shadow-lg z-10">
                                <ul class="py-2">
                                    <template x-for="item in results" :key="item.slug">
                                        <li>
                                            <a :href="item.url" class="flex items-center justify-between px-4 py-2 hover:bg-gray-50">
                                                <span class="text-sm text-gray-900" x-text="item.name"></span>
                                                <span class="text-xs text-gray-500" x-text="item.price"></span>
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                    </div>
                </form>
            </div>
        </header>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">Menampilkan {{ $products->total() }} produk</p>
                <a href="{{ route('catalog') }}" class="text-xs uppercase tracking-[0.5em] text-gray-500 hover:text-gray-900">Reset filter</a>
            </div>

            @php
                $queryString = fn ($overrides = []) => array_filter(
                    array_merge(
                        ['q' => $searchQuery],
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
            <form action="{{ route('catalog') }}" method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 bg-white border border-gray-200 rounded-3xl p-5 text-sm text-gray-700">
                <input type="hidden" name="q" value="{{ $searchQuery }}">
                @if ($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                @endif
                <label class="space-y-1">
                    <span class="text-xs uppercase tracking-[0.4em] text-gray-500">Min Price</span>
                    <input type="number" name="min_price" value="{{ $filters['min_price'] }}" class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="0">
                </label>
                <label class="space-y-1">
                    <span class="text-xs uppercase tracking-[0.4em] text-gray-500">Max Price</span>
                    <input type="number" name="max_price" value="{{ $filters['max_price'] }}" class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" placeholder="5000000">
                </label>
                <label class="flex items-center gap-2 text-xs uppercase tracking-[0.4em] text-gray-500">
                    <input type="checkbox" name="in_stock" value="1" {{ $filters['in_stock'] ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                    Ready Stock
                </label>
                <label class="flex items-center gap-2 text-xs uppercase tracking-[0.4em] text-gray-500">
                    <input type="checkbox" name="featured" value="1" {{ $filters['featured'] ? 'checked' : '' }} class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                    Featured
                </label>
                <div class="sm:col-span-2 lg:col-span-4 flex flex-wrap gap-3">
                    <button class="px-5 py-2 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em]">Apply</button>
                    <a href="{{ route('catalog') }}" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900">Reset</a>
                </div>
            </form>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($products as $product)
                <x-product.card :product="$product" />
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('catalogSearch', (endpoint, persisted) => ({
                query: persisted.q ?? '',
                results: [],
                fetchResults() {
                    if (!this.query || this.query.length < 2) {
                        this.results = [];
                        return;
                    }

                    fetch(`${endpoint}?q=${encodeURIComponent(this.query)}`)
                        .then(response => response.json())
                        .then(data => {
                            this.results = data.map(item => ({
                                ...item,
                                url: `/products/${item.slug}`,
                            }));
                        });
                },
            }));
        });
    </script>
@endpush

