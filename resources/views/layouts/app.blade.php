<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'gear-in') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
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
