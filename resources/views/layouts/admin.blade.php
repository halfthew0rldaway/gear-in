<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'gear-in admin')</title>
        
        <!-- Meta Description -->
        <meta name="description" content="@yield('description', 'gear-in Admin Panel - Manage products, orders, and customers.')">
        
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
    <body class="bg-[#f4f4f4] text-gray-900 antialiased">
        <div class="min-h-screen grid grid-cols-1 lg:grid-cols-[240px_1fr]">
            <aside class="border-b lg:border-b-0 lg:border-r border-gray-200 bg-white">
                <div class="px-6 py-6 flex items-center justify-between lg:justify-center">
                    <a href="{{ route('home') }}" class="text-2xl font-semibold tracking-tight">gear<span class="text-gray-400">-</span>in</a>
                    <span class="text-[11px] uppercase tracking-[0.3em] text-gray-400 lg:hidden">Admin</span>
                </div>
                <nav class="px-6 space-y-2 pb-6 text-sm uppercase tracking-[0.2em] text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">Produk</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">Kategori</a>
                    <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">Pesanan</a>
                    <a href="{{ route('admin.reviews.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">Reviews</a>
                    <a href="{{ route('admin.chat.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.chat.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">Chat
                        @php
                            $unreadCount = \App\Models\Conversation::whereHas('messages', function ($query) {
                                $query->where('is_read', false)
                                    ->whereHas('user', function ($q) {
                                        $q->where('role', \App\Models\User::ROLE_CUSTOMER);
                                    });
                            })->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </nav>
            </aside>

            <div class="flex flex-col">
                <header class="border-b border-gray-200 bg-white px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Panel Admin</p>
                        <h1 class="text-lg font-semibold">@yield('page-title', 'Overview')</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="text-xs uppercase tracking-[0.3em] border border-gray-900 px-3 py-1 rounded-full hover:bg-gray-900 hover:text-white transition">Logout</button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 p-6 space-y-6">
                    @if (session('status'))
                        <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-white border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
        @stack('scripts')
        
        <!-- Admin Chat Widget -->
        <x-admin-chat-widget />
    </body>
</html>

