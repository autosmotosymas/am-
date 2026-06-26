<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'AutosMotosYMás') }}</title>

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
</head>
<body class="font-sans antialiased bg-[#111111] min-h-screen flex flex-col">

    <div class="flex-1 flex flex-col md:flex-row">

        {{-- Panel izquierdo — branding (solo desktop) --}}
        <div class="hidden md:flex md:w-1/2 lg:w-[55%] bg-gradient-to-br from-[#0d0d0d] via-[#1a0f00] to-[#111111] flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 opacity-20"
                 style="background-image: radial-gradient(circle at 70% 30%, #E8710A 0%, transparent 55%);">
            </div>

            {{-- Contenido interno limitado a 560px alineado a la derecha del panel --}}
            <div class="relative flex flex-col justify-between h-full p-10 max-w-[560px] ml-auto w-full">
                {{-- Logo --}}
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo_amm.png') }}" alt="AutosMotosYMás" class="w-[180px] h-auto">
                </a>

                {{-- Copy central --}}
                <div>
                    <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight mb-4">
                        Vehículos<br>
                        <span class="text-brand-orange">certificados</span><br>
                        en Guadalajara
                    </h1>
                    <p class="text-gray-400 text-lg leading-relaxed max-w-sm">
                        Solo agencias verificadas. Inspección física real antes de publicar.
                    </p>

                    <div class="flex gap-8 mt-10">
                        <div>
                            <p class="text-2xl font-black text-white">100%</p>
                            <p class="text-xs text-gray-500 mt-0.5">Agencias verificadas</p>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">0</p>
                            <p class="text-xs text-gray-500 mt-0.5">Particulares</p>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-brand-orange">✓</p>
                            <p class="text-xs text-gray-500 mt-0.5">Certificación física</p>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-600">
                    &copy; {{ date('Y') }} AutosMotosYMás.com.mx
                </p>
            </div>
        </div>

        {{-- Panel derecho — formulario (fondo oscuro fijo para que inputs text-white sean legibles) --}}
        <div class="flex-1 flex flex-col justify-center px-6 py-12 md:px-12 lg:px-16 bg-[#111111]">

            {{-- Logo móvil --}}
            <div class="md:hidden mb-8">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo_amm.png') }}" alt="AutosMotosYMás" class="w-[160px] h-auto">
                </a>
            </div>

            {{-- Formulario limitado a max-w-md, alineado a la izquierda del panel --}}
            <div class="w-full max-w-md mx-auto md:mx-0 md:max-w-[420px]">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
