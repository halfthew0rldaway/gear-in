<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'gear-in')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-[#f7f7f7] text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col">
            <header class="sticky top-0 z-40 border-b border-gray-200/70 bg-white/70 backdrop-blur-md">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-2xl font-semibold tracking-tight">gear<span class="text-gray-400">-</span>in</a>
                    <nav class="hidden md:flex items-center gap-8 text-sm uppercase tracking-wide text-gray-500">
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-gray-900' : 'hover:text-gray-900' }} link-underline">Beranda</a>
                        <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'text-gray-900' : 'hover:text-gray-900' }} link-underline">Katalog</a>
                        @auth
                            @if (auth()->user()->isCustomer())
                                <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'text-gray-900' : 'hover:text-gray-900' }} link-underline">Pesanan</a>
                                <a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.*') ? 'text-gray-900' : 'hover:text-gray-900' }} link-underline">Keranjang</a>
                                <a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.*') ? 'text-gray-900' : 'hover:text-gray-900' }} link-underline">Wishlist</a>
                                <a href="{{ route('chat.index') }}" class="{{ request()->routeIs('chat.*') ? 'text-gray-900' : 'hover:text-gray-900' }} link-underline">Chat</a>
                            @else
                                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'text-gray-900' : 'hover:text-gray-900' }} link-underline">Admin</a>
                            @endif
                        @endauth
                    </nav>
                    <div class="flex items-center gap-3 text-sm">
                        @auth
                            <span class="hidden sm:inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-600">{{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="text-gray-900 border border-gray-900 px-3 py-1 rounded-full hover:bg-gray-900 hover:text-white transition">Keluar</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Masuk</a>
                            <a href="{{ route('register') }}" class="px-3 py-1 rounded-full bg-gray-900 text-white hover:bg-black transition">Daftar</a>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="flex-1">
                @if (session('status'))
                    <div class="max-w-4xl mx-auto mt-6 px-4 notification-slide">
                        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-900">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">✓</span>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="max-w-4xl mx-auto mt-6 px-4 notification-slide">
                        <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600">!</span>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    </div>
                @endif
                @yield('content')
            </main>

            <footer class="border-t border-gray-200 bg-white">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-xs uppercase tracking-[0.2em] text-gray-400 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <span>gear-in · curated gaming essentials</span>
                    <span>© {{ date('Y') }} gear-in</span>
                </div>
            </footer>
        </div>
        @stack('scripts')
        
        <!-- Chat Widget -->
        <x-chat-widget />
    </body>
</html>

