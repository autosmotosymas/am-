<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal Agencia' }} — AutosMotosYMás</title>

    <script>
        (function () {
            @isset($temaScript)
                localStorage.setItem('amm_tema', '{{ $temaScript }}');
            @endisset
            const stored = localStorage.getItem('amm_tema');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const useDark = stored === 'dark' || (stored !== 'light' && prefersDark);
            if (useDark) document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-page text-base" x-data="{ sidebarOpen: false }">

    {{-- Overlay móvil --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/60 z-30 lg:hidden"></div>

    <div class="flex h-screen overflow-hidden">

        {{-- ══════════ SIDEBAR ══════════ --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-card border-r border-base flex flex-col
                      transform transition-transform duration-200
                      lg:static lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

            {{-- Logo --}}
            <div class="flex items-center justify-between h-16 px-5 border-b border-base shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('img/logo_amm.png') }}" alt="AutosMotosYMás" class="h-8 w-auto">
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden btn-ghost w-8 h-8 p-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Info agencia --}}
            @php $agenciaNav = auth()->user()->agencia; @endphp
            @if($agenciaNav)
                <div class="px-4 py-4 border-b border-base">
                    <p class="text-xs text-muted mb-0.5">Agencia</p>
                    <p class="text-sm font-semibold text-base truncate">{{ $agenciaNav->nombre }}</p>
                    @if($agenciaNav->suscripcionActiva)
                        <span class="inline-block mt-1 text-[10px] font-semibold px-2 py-0.5 rounded-full
                            {{ $agenciaNav->suscripcionActiva->plan?->badge_premium ? 'bg-yellow-400/15 text-yellow-400 border border-yellow-400/30' : 'bg-brand-orange/10 text-brand-orange border border-brand-orange/20' }}">
                            {{ $agenciaNav->suscripcionActiva->plan?->nombre ?? 'Básico' }}
                        </span>
                    @else
                        <span class="inline-block mt-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                            Sin suscripción
                        </span>
                    @endif
                </div>
            @endif

            {{-- Nav links --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
                @php
                    $navItems = [
                        ['route' => 'agencia.dashboard',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
                        ['route' => 'agencia.vehiculos.index', 'icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12', 'label' => 'Inventario'],
                        ['route' => 'agencia.leads.index',   'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75', 'label' => 'Leads'],
                        ['route' => 'agencia.estadisticas',    'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z', 'label' => 'Estadísticas'],
                        ['route' => 'agencia.suscripcion.index', 'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z', 'label' => 'Suscripción'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                              {{ request()->routeIs($item['route'] . '*')
                                  ? 'bg-brand-orange/10 text-brand-orange'
                                  : 'text-muted hover:text-base hover:bg-card2' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Footer sidebar --}}
            <div class="border-t border-base p-4 space-y-1 shrink-0">
                <a href="{{ route('home') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs text-muted hover:text-base hover:bg-card2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    Ver sitio público
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs text-red-400 hover:bg-red-500/5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                        </svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- ══════════ CONTENIDO ══════════ --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Topbar --}}
            <header class="h-16 bg-card border-b border-base flex items-center justify-between px-4 md:px-6 shrink-0">
                <button @click="sidebarOpen = true" class="btn-ghost w-9 h-9 p-0 lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                <div class="flex-1 lg:flex-none">
                    <h1 class="text-sm font-semibold text-base">{{ $title ?? 'Portal Agencia' }}</h1>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Toggle tema --}}
                    <button
                        @click="
                            const html = document.documentElement;
                            const isDark = html.classList.toggle('dark');
                            localStorage.setItem('amm_tema', isDark ? 'dark' : 'light');
                            fetch('{{ route('perfil.tema') }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                                body: JSON.stringify({ tema: isDark ? 'dark' : 'light' })
                            });
                        "
                        class="btn-ghost w-9 h-9 p-0">
                        <svg class="hidden dark:block w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z"/>
                        </svg>
                        <svg class="block dark:hidden w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/>
                        </svg>
                    </button>

                    <div class="w-8 h-8 rounded-full bg-brand-orange flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Alerta flash --}}
            @if(session('ok'))
                <div class="mx-4 md:mx-6 mt-4 bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-xl px-4 py-3">
                    {{ session('ok') }}
                </div>
            @endif

            {{-- Main content --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
