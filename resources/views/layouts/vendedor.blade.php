<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#111111">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AMM Vendedor">
    <title>{{ $title ?? 'Vendedor' }} — AMM</title>

    <link rel="apple-touch-icon" href="/img/icons/icon-192.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/img/icons/icon-192.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-[#111111] text-white min-h-screen flex flex-col max-w-lg mx-auto">

    {{-- Top bar --}}
    <header class="sticky top-0 z-10 bg-[#111111]/95 backdrop-blur-sm border-b border-white/10 px-4 flex items-center h-14 gap-3 safe-top">
        @if($back)
            <a href="{{ $back }}" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </a>
        @else
            <img src="{{ asset('img/logo_amm.png') }}" alt="AMM" class="h-7 w-auto shrink-0">
        @endif

        <h1 class="flex-1 text-base font-semibold truncate">{{ $title ?? 'Vendedor' }}</h1>

        <div class="flex items-center gap-2">
            @isset($headerAction)
                {{ $headerAction }}
            @endisset
            <div class="w-8 h-8 rounded-full bg-brand-orange flex items-center justify-center text-white text-xs font-bold shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </header>

    {{-- Flash --}}
    @if(session('ok'))
        <div class="mx-4 mt-4 bg-green-500/15 border border-green-500/30 text-green-400 text-sm rounded-2xl px-4 py-3">
            {{ session('ok') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mx-4 mt-4 bg-red-500/15 border border-red-500/30 text-red-400 text-sm rounded-2xl px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    {{-- Contenido --}}
    <main class="flex-1 pb-24">
        {{ $slot }}
    </main>

    {{-- Bottom nav --}}
    <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-lg bg-[#1a1a1a]/95 backdrop-blur-sm border-t border-white/10 flex safe-bottom">

        {{-- Dashboard --}}
        <a href="{{ route('vendedor.dashboard') }}"
           class="flex-1 flex flex-col items-center gap-1 py-3 text-xs transition-colors
                  {{ request()->routeIs('vendedor.dashboard') ? 'text-brand-orange' : 'text-gray-500 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                 stroke-width="{{ request()->routeIs('vendedor.dashboard') ? '2.5' : '2' }}" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Inicio
        </a>

        {{-- Nueva agencia (botón central destacado) --}}
        <a href="{{ route('vendedor.agencias.create') }}"
           class="flex-1 flex flex-col items-center justify-center py-2">
            <span class="w-12 h-12 rounded-2xl bg-brand-orange flex items-center justify-center shadow-lg shadow-brand-orange/30 -mt-5">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </span>
            <span class="text-xs text-gray-500 mt-1">Nueva agencia</span>
        </a>

        {{-- Perfil --}}
        <a href="{{ route('vendedor.perfil') }}"
           class="flex-1 flex flex-col items-center gap-1 py-3 text-xs transition-colors
                  {{ request()->routeIs('vendedor.perfil*') ? 'text-brand-orange' : 'text-gray-500 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                 stroke-width="{{ request()->routeIs('vendedor.perfil*') ? '2.5' : '2' }}" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
            </svg>
            Perfil
        </a>
    </nav>

    @stack('scripts')
</body>
</html>
