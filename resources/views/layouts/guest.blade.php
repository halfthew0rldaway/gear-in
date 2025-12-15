<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'gear-in') }}</title>

    <!-- Meta Description -->
    <meta name="description"
        content="gear-in - Curated gaming essentials for your setup. Discover premium gaming gear and accessories.">

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
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap"
            rel="stylesheet">
    </noscript>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-950">
    <div class="relative min-h-screen flex flex-col items-center justify-center px-4 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="auth-radial-gradient"></div>
            <canvas id="node-background" class="auth-node-canvas"></canvas>
            <div class="matrix-grid"></div>
            <div class="auth-noise-overlay"></div>
            <div class="auth-vignette"></div>
        </div>
        <a href="/"
            class="relative text-2xl font-semibold tracking-tight mb-6 text-white drop-shadow-[0_3px_12px_rgba(15,23,42,0.6)]">
            gear<span class="text-gray-500">-</span>in
        </a>
        <div
            class="relative w-full sm:max-w-md bg-white/95 border border-white/40 rounded-[32px] p-8 shadow-[0_40px_120px_rgb(15,23,42,0.65)] backdrop-blur-2xl animate-form-entrance">
            {{ $slot }}
        </div>
    </div>
</body>

</html>