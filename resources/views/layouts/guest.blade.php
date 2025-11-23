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
    <body class="font-sans text-gray-900 antialiased bg-gray-950">
        <div class="relative min-h-screen flex flex-col items-center justify-center px-4 overflow-hidden">
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                <div class="auth-radial-gradient"></div>
                <canvas id="node-background" class="auth-node-canvas"></canvas>
                <div class="matrix-grid"></div>
                <div class="auth-noise-overlay"></div>
                <div class="auth-vignette"></div>
            </div>
            <a href="/" class="relative text-2xl font-semibold tracking-tight mb-6 text-white drop-shadow-[0_3px_12px_rgba(15,23,42,0.6)]">
                gear<span class="text-gray-500">-</span>in
            </a>
            <div class="relative w-full sm:max-w-md bg-white/95 border border-white/40 rounded-[32px] p-8 shadow-[0_40px_120px_rgb(15,23,42,0.65)] backdrop-blur-2xl">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-500 mb-4">Akses</p>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
