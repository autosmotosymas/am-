<!DOCTYPE html>
<html lang="es" x-data x-bind:class="{ 'dark': $store.tema.dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?: config('app.name', 'AutosMotosYMás') }}</title>
    <meta name="description" content="{{ $metaDesc ?: 'Compra tu auto seminuevo certificado en Guadalajara. Solo agencias verificadas.' }}">

    {{-- Script anti-flash dark mode — debe ir ANTES del CSS --}}
    <script>
        (function () {
            @isset($temaScript)
            // Sincroniza localStorage con la preferencia guardada en BD (post-login)
            localStorage.setItem('amm_tema', '{{ $temaScript }}');
            @endisset
            const stored = localStorage.getItem('amm_tema');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const useDark = stored === 'dark' || (stored !== 'light' && prefersDark);
            if (useDark) document.documentElement.classList.add('dark');
        })();
    </script>

    {{-- Fuentes --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- SEO extra por vista --}}
    @stack('head')
</head>
<body class="font-sans antialiased bg-page text-base min-h-screen flex flex-col">

    {{-- ======================== NAVBAR ======================== --}}
    <header
        class="sticky top-0 z-50 bg-white/90 dark:bg-[#111111]/95 backdrop-blur-sm border-b border-base"
        x-data="{ mobileOpen: false }"
    >
        <nav class="container-amm flex items-center justify-between py-[30px]">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center shrink-0">
                <img src="{{ asset('img/logo_amm.png') }}" alt="AutosMotosYMás" class="w-[200px] h-auto">
            </a>

            {{-- Links desktop --}}
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('home') }}"
                   class="btn-ghost text-base font-semibold {{ request()->routeIs('home') ? 'text-brand-orange' : '' }}">
                    Inicio
                </a>
                <a href="{{ route('busqueda') }}"
                   class="btn-ghost text-base font-semibold {{ request()->routeIs('busqueda') ? 'text-brand-orange' : '' }}">
                    Buscar autos
                </a>
            </div>

            {{-- Acciones derechas --}}
            <div class="flex items-center gap-2">

                {{-- Toggle dark/light --}}
                <button
                    @click="
                        const html = document.documentElement;
                        const isDark = html.classList.toggle('dark');
                        localStorage.setItem('amm_tema', isDark ? 'dark' : 'light');
                        @auth
                        fetch('{{ route('perfil.tema') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                            body: JSON.stringify({ tema: isDark ? 'dark' : 'light' })
                        });
                        @endauth
                    "
                    class="btn-ghost w-9 h-9 p-0 rounded-lg"
                    title="Cambiar tema"
                    aria-label="Cambiar entre modo oscuro y claro"
                >
                    {{-- Ícono sol (visible en dark) --}}
                    <svg class="hidden dark:block w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z"/>
                    </svg>
                    {{-- Ícono luna (visible en light) --}}
                    <svg class="block dark:hidden w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/>
                    </svg>
                </button>

                @auth
                    {{-- Menú usuario --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="btn-ghost flex items-center gap-2 h-9 px-3">
                            <span class="text-sm font-medium hidden sm:inline">
                                {{ Str::words(Auth::user()->name, 1, '') }}
                            </span>
                            <div class="w-7 h-7 rounded-full bg-brand-orange flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-transition
                             class="absolute right-0 mt-2 w-56 bg-card border border-base rounded-xl shadow-lg py-1 z-50">

                            @hasrole('admin')
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex items-center gap-2 px-4 py-3 text-base text-base hover:bg-card2 transition-colors">
                                    Panel Admin
                                </a>
                            @endhasrole

                            @hasrole('agencia')
                                <a href="{{ route('agencia.dashboard') }}"
                                   class="flex items-center gap-2 px-4 py-3 text-base text-base hover:bg-card2 transition-colors">
                                    Mi Agencia
                                </a>
                            @endhasrole

                            @hasrole('capturador')
                                <a href="{{ route('captura.index') }}"
                                   class="flex items-center gap-2 px-4 py-3 text-base text-base hover:bg-card2 transition-colors">
                                    Captura
                                </a>
                            @endhasrole

                            <a href="{{ route('perfil.index') }}"
                               class="flex items-center gap-2 px-4 py-3 text-base text-base hover:bg-card2 transition-colors">
                                Mi perfil
                            </a>

                            <div class="border-t border-base my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left flex items-center gap-2 px-4 py-3 text-base text-red-500 hover:bg-card2 transition-colors">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost hidden sm:inline-flex">Entrar</a>
                    <a href="{{ route('register') }}" class="btn-primary">Publicar autos</a>
                @endauth

                {{-- Hamburguesa móvil --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="btn-ghost w-9 h-9 p-0 md:hidden"
                        aria-label="Menú">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Menú móvil --}}
        <div x-show="mobileOpen" x-transition class="md:hidden border-t border-base bg-card px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-3 text-base font-semibold text-base hover:text-brand-orange rounded-xl hover:bg-card2 transition-colors">
                Inicio
            </a>
            <a href="{{ route('busqueda') }}" class="block px-3 py-3 text-base font-semibold text-base hover:text-brand-orange rounded-xl hover:bg-card2 transition-colors">
                Buscar autos
            </a>
            @guest
                <div class="border-t border-base pt-3 mt-2 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="btn-outline w-full justify-center">Entrar</a>
                    <a href="{{ route('register') }}" class="btn-primary w-full justify-center">Publicar autos</a>
                </div>
            @endguest
        </div>
    </header>
    {{-- ====================== FIN NAVBAR ====================== --}}

    {{-- Contenido principal --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- ======================== FOOTER ======================== --}}
    <footer class="bg-card border-t border-base mt-auto">
        <div class="container-amm py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                {{-- Marca --}}
                <div class="lg:col-span-1">
                    <a href="{{ route('home') }}" class="inline-block mb-3">
                        <img src="{{ asset('img/logo_amm.png') }}" alt="AutosMotosYMás" class="w-[200px] h-auto">
                    </a>
                    <p class="text-muted text-base leading-relaxed">
                        Plataforma de vehículos seminuevos certificados en Guadalajara y Zona Metropolitana.
                    </p>
                </div>

                {{-- Explorar --}}
                <div>
                    <h3 class="text-base font-bold text-base mb-4">Explorar</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('busqueda') }}" class="text-base text-muted hover:text-brand-orange transition-colors">Buscar autos</a></li>
                        <li><a href="{{ route('busqueda', ['tipo' => 'sedan']) }}" class="text-base text-muted hover:text-brand-orange transition-colors">Sedanes</a></li>
                        <li><a href="{{ route('busqueda', ['tipo' => 'suv']) }}" class="text-base text-muted hover:text-brand-orange transition-colors">SUVs</a></li>
                        <li><a href="{{ route('busqueda', ['tipo' => 'pickup']) }}" class="text-base text-muted hover:text-brand-orange transition-colors">Pickups</a></li>
                    </ul>
                </div>

                {{-- Agencias --}}
                <div>
                    <h3 class="text-base font-bold text-base mb-4">Para agencias</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('register') }}" class="text-base text-muted hover:text-brand-orange transition-colors">Publicar inventario</a></li>
                        <li><a href="{{ route('login') }}" class="text-base text-muted hover:text-brand-orange transition-colors">Acceso agencia</a></li>
                    </ul>
                </div>

                {{-- Contacto / Legal --}}
                <div>
                    <h3 class="text-base font-bold text-base mb-4">Contacto</h3>
                    <ul class="space-y-2">
                        <li>
                            @if(request()->routeIs('home'))
                                <a href="#contacto" class="text-base text-muted hover:text-brand-orange transition-colors">Escríbenos</a>
                            @else
                                <a href="{{ route('home') }}#contacto" class="text-base text-muted hover:text-brand-orange transition-colors">Escríbenos</a>
                            @endif
                        </li>
                        <li><a href="#" class="text-base text-muted hover:text-brand-orange transition-colors">Aviso de privacidad</a></li>
                        <li><a href="#" class="text-base text-muted hover:text-brand-orange transition-colors">Términos y condiciones</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-base mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-muted">
                    &copy; {{ date('Y') }} AutosMotosYMás.com.mx — Guadalajara, Jalisco
                </p>
                <p class="text-sm text-muted">
                    Vehículos <span class="text-brand-orange font-medium">certificados</span> por talleres aliados
                </p>
            </div>
        </div>
    </footer>
    {{-- ====================== FIN FOOTER ====================== --}}

    @stack('scripts')
</body>
</html>
