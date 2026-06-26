<x-app-layout title="Autos seminuevos certificados en Guadalajara — AutosMotosYMás"
              meta_desc="Compra tu auto seminuevo certificado en Guadalajara. Solo agencias verificadas. Búsqueda, ficha y contacto directo.">

    {{-- ══════════════════════════════════════════════════
         HERO
    ══════════════════════════════════════════════════ --}}
    <section class="relative bg-[#111111] overflow-hidden"
             style="min-height: clamp(480px, 70vh, 720px);"
             x-data="{ idx: 0, going: false }"
             x-init="
                 $nextTick(() => {
                     const track = $refs.track;
                     track.addEventListener('transitionend', () => {
                         if (idx === 3) { going = false; idx = 0; }
                     });
                     setInterval(() => { going = true; idx++; }, 5500);
                 });
             ">

        {{-- Slider: banner01, banner02, banner03, clon de banner01 — loop infinito --}}
        <div class="absolute inset-0">
            <div x-ref="track"
                 class="flex h-full"
                 style="width: 400%"
                 :style="`transform: translateX(-${idx * 25}%); transition: ${going ? 'transform 1.1s cubic-bezier(0.77,0,0.175,1)' : 'none'}`">
                @foreach(['banner01', 'banner02', 'banner03', 'banner01'] as $banner)
                    <div class="h-full" style="width: 25%; flex-shrink: 0;">
                        <img src="{{ asset('img/banners/' . $banner . '.jpg') }}"
                             alt=""
                             class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
            {{-- Overlay oscuro para legibilidad del texto --}}
            <div class="absolute inset-0 bg-black/30"></div>
        </div>

        <div class="container-amm relative py-20 md:py-28">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 bg-black/70 border border-brand-orange/60 text-brand-orange text-base font-semibold px-4 py-2 rounded-full mb-6">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/>
                    </svg>
                    Solo agencias verificadas · Guadalajara ZMG
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6"
                    style="text-shadow: 2px 3px 0 rgba(0,0,0,0.4)">
                    Tu próximo auto,
                    <span class="text-brand-orange">certificado</span>
                    y sin sorpresas
                </h1>

                <p class="text-xl mb-8 leading-relaxed" style="color:#fafafa; text-shadow: 2px 3px 0 rgba(0,0,0,0.4)">
                    Compramos con confianza. Cada vehículo pasa por inspección física en talleres aliados
                    antes de publicarse. Solo agencias profesionales, cero particulares.
                </p>

                {{-- Buscador rápido --}}
                <form action="{{ route('busqueda') }}" method="GET"
                      class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="q"
                           placeholder="Marca, modelo o año…"
                           class="flex-1 bg-black/50 border border-brand-orange/60 text-white placeholder-gray-300 rounded-lg px-4 py-3 text-base focus:outline-none focus:border-brand-orange focus:bg-black/60 transition-colors">
                    <button type="submit" class="btn-primary whitespace-nowrap px-6 py-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        Buscar
                    </button>
                </form>

                {{-- Filtros rápidos --}}
                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach(['SUV', 'Sedan', 'Pickup', 'Menos de $200k', 'Automático'] as $filtro)
                        <a href="{{ route('busqueda', ['q' => $filtro]) }}"
                           class="text-sm text-white hover:text-brand-orange bg-black/20 hover:bg-black/90 border border-brand-orange/60 hover:border-brand-orange px-4 py-2 rounded-full transition-colors">
                            {{ $filtro }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Stats bar --}}
        <div class="relative border-t border-white/10 bg-white/5 backdrop-blur-sm">
            <div class="container-amm py-5">
                <div class="grid grid-cols-3 gap-4 max-w-lg">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-white">{{ number_format($stats['vehiculos']) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Vehículos</p>
                    </div>
                    <div class="text-center border-x border-white/10">
                        <p class="text-2xl font-bold text-white">{{ number_format($stats['agencias']) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Agencias</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-brand-orange">{{ number_format($stats['certificados']) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Certificados</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         VEHÍCULOS DESTACADOS
    ══════════════════════════════════════════════════ --}}
    <section class="section-py bg-page">
        <div class="container-amm">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <p class="text-xs font-semibold text-brand-orange uppercase tracking-widest mb-1">Inventario</p>
                    <h2 class="text-2xl md:text-3xl font-bold text-base">Autos disponibles</h2>
                </div>
                <a href="{{ route('busqueda') }}" class="btn-outline text-sm hidden sm:inline-flex">
                    Ver todos
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            @if($destacados->isEmpty())
                <div class="text-center py-20 text-muted">
                    <svg class="w-12 h-12 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                    <p class="text-sm">Pronto habrá vehículos disponibles.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($destacados as $v)
                        <x-tarjeta-vehiculo :vehiculo="$v" />
                    @endforeach
                </div>

                <div class="text-center mt-8 sm:hidden">
                    <a href="{{ route('busqueda') }}" class="btn-outline">Ver todos los autos</a>
                </div>
            @endif
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         CÓMO FUNCIONA LA CERTIFICACIÓN
    ══════════════════════════════════════════════════ --}}
    <section class="section-py bg-card border-y border-base relative overflow-hidden">

        {{-- Silueta decorativa: libreta con checkpoints --}}
        <svg class="absolute -left-16 top-1/2 -translate-y-1/2 h-[90%] w-auto pointer-events-none select-none opacity-[0.055] text-gray-500 dark:text-gray-300"
             viewBox="0 0 300 420" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            {{-- Cuerpo de la libreta --}}
            <rect x="40" y="70" width="230" height="330" rx="14" stroke="currentColor" stroke-width="8"/>
            {{-- Clip superior --}}
            <rect x="108" y="38" width="94" height="50" rx="10" stroke="currentColor" stroke-width="7"/>
            <circle cx="155" cy="63" r="10" stroke="currentColor" stroke-width="5"/>
            {{-- Fila 1 — marcada --}}
            <circle cx="85" cy="148" r="13" stroke="currentColor" stroke-width="5"/>
            <path d="M77 148 L83 155 L94 140" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            <line x1="115" y1="148" x2="248" y2="148" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
            {{-- Fila 2 — marcada --}}
            <circle cx="85" cy="210" r="13" stroke="currentColor" stroke-width="5"/>
            <path d="M77 210 L83 217 L94 202" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            <line x1="115" y1="210" x2="248" y2="210" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
            {{-- Fila 3 — pendiente --}}
            <circle cx="85" cy="272" r="13" stroke="currentColor" stroke-width="5"/>
            <line x1="115" y1="272" x2="248" y2="272" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
            {{-- Fila 4 — marcada --}}
            <circle cx="85" cy="334" r="13" stroke="currentColor" stroke-width="5"/>
            <path d="M77 334 L83 341 L94 326" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            <line x1="115" y1="334" x2="248" y2="334" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
        </svg>

        <div class="container-amm">
            <div class="text-center max-w-xl mx-auto mb-12">
                <p class="text-xs font-semibold text-brand-orange uppercase tracking-widest mb-2">Nuestro diferenciador</p>
                <h2 class="text-2xl md:text-3xl font-bold text-base mb-3">
                    Certificación física, no solo fotos
                </h2>
                <p class="text-muted text-sm leading-relaxed">
                    Un taller verificador aliado inspecciona cada vehículo en persona antes de que aparezca con badge verde en el catálogo.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['num' => '01', 'titulo' => 'Agencia publica el vehículo', 'desc' => 'La agencia captura fotos, especificaciones y precio desde su portal o app móvil.', 'icon' => 'M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z'],
                    ['num' => '02', 'titulo' => 'Inspector acude al lote', 'desc' => 'Un verificador aliado de AutosMotosYMás inspecciona motor, carrocería, interiores y documentos.', 'icon' => 'M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75'],
                    ['num' => '03', 'titulo' => 'Badge verde en el catálogo', 'desc' => 'El vehículo aprobado recibe el badge "✓ Certificado AMM" y acceso prioritario en búsquedas.', 'icon' => 'M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z'],
                ] as $paso)
                    <div class="relative bg-card2 border border-base rounded-2xl p-6">
                        <span class="absolute top-5 right-5 text-4xl font-black text-brand-orange/10 select-none">{{ $paso['num'] }}</span>
                        <div class="w-[66px] h-[66px] rounded-xl bg-brand-orange/10 border border-brand-orange/20 flex items-center justify-center mb-4">
                            <svg class="w-[30px] h-[30px] text-brand-orange" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $paso['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-base mb-2">{{ $paso['titulo'] }}</h3>
                        <p class="text-sm text-muted leading-relaxed">{{ $paso['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         VEHÍCULOS CERTIFICADOS
    ══════════════════════════════════════════════════ --}}
    @if($certificados->isNotEmpty())
    <section class="section-py bg-page">
        <div class="container-amm">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <p class="text-xs font-semibold text-brand-orange uppercase tracking-widest mb-1">Con respaldo</p>
                    <h2 class="text-2xl md:text-3xl font-bold text-base">
                        <span class="badge-cert inline-flex mr-2">✓ Certificados</span>
                        esta semana
                    </h2>
                </div>
                <a href="{{ route('busqueda', ['certificado' => 1]) }}" class="btn-outline text-sm hidden sm:inline-flex">
                    Ver certificados
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($certificados as $v)
                    <x-tarjeta-vehiculo :vehiculo="$v" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════════
         CONTACTO
    ══════════════════════════════════════════════════ --}}
    <section id="contacto" class="section-py bg-card border-y border-base">
        <div class="container-amm">
            <div class="max-w-xl mx-auto">
                <div class="text-center mb-8">
                    <p class="text-xs font-semibold text-brand-orange uppercase tracking-widest mb-2">¿Dudas o comentarios?</p>
                    <h2 class="text-2xl md:text-3xl font-bold text-base">Contáctanos</h2>
                </div>

                @if(session('contacto_ok'))
                    <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-2xl px-6 py-5 text-center mb-6">
                        <p class="font-semibold">¡Mensaje enviado!</p>
                        <p class="text-sm mt-1">Nos pondremos en contacto contigo pronto.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('contacto.store') }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="label-amm">Nombre *</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                       class="input-amm w-full" placeholder="Tu nombre">
                                @error('nombre')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label-amm">Teléfono *</label>
                                <input type="tel" name="telefono" value="{{ old('telefono') }}" required
                                       class="input-amm w-full" placeholder="33 1234 5678">
                                @error('telefono')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="label-amm">Correo electrónico *</label>
                            <input type="email" name="correo" value="{{ old('correo') }}" required
                                   class="input-amm w-full" placeholder="tu@correo.com">
                            @error('correo')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="label-amm">Comentarios *</label>
                            <textarea name="comentarios" rows="4" required
                                      class="input-amm w-full resize-none"
                                      placeholder="¿En qué podemos ayudarte?">{{ old('comentarios') }}</textarea>
                            @error('comentarios')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="btn-primary w-full justify-center">
                            Enviar mensaje
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         CTA AGENCIAS
    ══════════════════════════════════════════════════ --}}
    <section class="relative min-h-screen flex items-center"
             style="background-image: url('{{ asset('img/banners/agencia.jpg') }}'); background-attachment: fixed; background-size: cover; background-position: center;">

        {{-- Overlay negro 30% --}}
        <div class="absolute inset-0" style="background-color: rgba(0,0,0,0.30);"></div>

        <div class="container-amm relative w-full py-20">
            <div class="relative overflow-hidden rounded-2xl p-10 md:p-16 text-center"
                 style="background-color: rgba(232,113,10,0.55);">
                <div class="relative">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        ¿Tienes agencia o lote?
                    </h2>
                    <p class="text-lg font-bold text-white mb-8 max-w-xl mx-auto leading-relaxed">
                        Publica tu inventario, recibe leads calificados y diferénciate con la certificación física.
                        Planes desde $599/mes.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 bg-white text-brand-orange font-semibold px-7 py-3 rounded-lg hover:bg-orange-50 transition-colors">
                            Empezar gratis
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                        <a href="#como-funciona"
                           class="inline-flex items-center justify-center gap-2 bg-black/20 hover:bg-black/40 text-white font-medium px-7 py-3 rounded-lg transition-colors border border-white/30">
                            Ver planes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>
