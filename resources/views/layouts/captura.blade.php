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
    <meta name="apple-mobile-web-app-title" content="AMM Captura">
    <title>{{ $title ?? 'Captura' }} — AMM</title>

    <link rel="manifest" href="/pwa/manifest.json">
    <link rel="apple-touch-icon" href="/img/icons/icon-192.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/img/icons/icon-192.png">

    <script>
        (function () {
            const stored = localStorage.getItem('amm_tema');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'light' || (stored !== 'dark' && !prefersDark)) {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-[#111111] text-white min-h-screen flex flex-col max-w-lg mx-auto">

    {{-- Top bar --}}
    <header class="sticky top-0 z-10 bg-[#111111]/95 backdrop-blur-sm border-b border-white/10 px-4 flex items-center h-14 gap-3 safe-top">
        @isset($back)
            <a href="{{ $back }}" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </a>
        @else
            <img src="{{ asset('img/logo_amm.png') }}" alt="AMM" class="h-7 w-auto shrink-0">
        @endisset

        <h1 class="flex-1 text-base font-semibold truncate">{{ $title ?? 'Captura' }}</h1>

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
        <a href="{{ route('captura.index') }}"
           class="flex-1 flex flex-col items-center gap-1 py-3 text-xs transition-colors
                  {{ request()->routeIs('captura.index') ? 'text-brand-orange' : 'text-gray-500 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('captura.index') ? '2.5' : '2' }}" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0z"/>
            </svg>
            Mi lista
        </a>

        <a href="{{ route('captura.create') }}"
           class="flex-1 flex flex-col items-center justify-center py-2">
            <span class="w-12 h-12 rounded-2xl bg-brand-orange flex items-center justify-center shadow-lg shadow-brand-orange/30 -mt-5">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </span>
            <span class="text-xs text-gray-500 mt-1">Nuevo</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="flex-1">
            @csrf
            <button type="submit"
                    class="w-full h-full flex flex-col items-center gap-1 py-3 text-xs text-gray-500 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                </svg>
                Salir
            </button>
        </form>
    </nav>

    @stack('scripts')
</body>
</html>
