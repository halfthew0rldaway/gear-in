<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'gear-in') }}</title>
        
        <!-- Meta Description -->
        <meta name="description" content="@yield('description', 'gear-in - Curated gaming essentials for your setup. Discover premium gaming gear and accessories.')">
        
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}">
        
        <!-- Preconnect untuk fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <!-- Preload fonts untuk performance -->
        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet"></noscript>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-[#f7f7f7] text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col">
            <header class="border-b border-gray-200 bg-white">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-2xl font-semibold tracking-tight">gear<span class="text-gray-400">-</span>in</a>
                    <nav class="flex items-center gap-4 text-xs uppercase tracking-[0.4em] text-gray-400">
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-gray-900' : '' }}">Store</a>
                        <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'text-gray-900' : '' }}">Pesanan</a>
                        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'text-gray-900' : '' }}">Profil</a>
                    </nav>
                </div>
            </header>

            <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
