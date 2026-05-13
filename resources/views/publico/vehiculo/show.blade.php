@php
    $titulo = "{$vehiculo->anio} {$vehiculo->marca} {$vehiculo->modelo}" . ($vehiculo->version ? " {$vehiculo->version}" : '');
    $cert    = $vehiculo->certificacion;
    $agencia = $vehiculo->agencia;
    $fotos   = $vehiculo->fotos;
@endphp

<x-app-layout :title="$titulo . ' — AutosMotosYMás'"
              :meta-desc="'Compra el ' . $titulo . ' en ' . ($agencia->ciudad ?? 'Guadalajara') . '. ' . ($cert ? 'Certificado por taller aliado. ' : '') . $vehiculo->precio_formateado . '.'">

<div class="bg-page">
    <div class="container-amm py-6 md:py-10">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-1.5 text-xs text-muted mb-6">
            <a href="{{ route('home') }}" class="hover:text-brand-orange transition-colors">Inicio</a>
            <span>/</span>
            <a href="{{ route('busqueda') }}" class="hover:text-brand-orange transition-colors">Buscar</a>
            <span>/</span>
            <a href="{{ route('busqueda', ['marca' => $vehiculo->marca]) }}" class="hover:text-brand-orange transition-colors">{{ $vehiculo->marca }}</a>
            <span>/</span>
            <span class="text-base truncate max-w-[160px]">{{ $titulo }}</span>
        </nav>

        {{-- ══════════════ LAYOUT PRINCIPAL ══════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start">

            {{-- ── COLUMNA IZQUIERDA ── --}}
            <div class="space-y-6">

                {{-- GALERÍA --}}
                <div x-data="{ activa: 0, fotos: {{ $fotos->map(fn($f) => $f->url)->toJson() }} }"
                     class="space-y-3">

                    {{-- Foto principal --}}
                    <div class="relative aspect-[16/10] bg-card2 rounded-2xl overflow-hidden group">
                        <template x-if="fotos.length > 0">
                            <img :src="fotos[activa]"
                                 :alt="'{{ addslashes($titulo) }} — foto ' + (activa + 1)"
                                 class="w-full h-full object-cover">
                        </template>
                        <template x-if="fotos.length === 0">
                            <div class="w-full h-full flex items-center justify-center text-gray-600">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                </svg>
                            </div>
                        </template>

                        {{-- Flechas navegación --}}
                        <template x-if="fotos.length > 1">
                            <div>
                                <button @click="activa = (activa - 1 + fotos.length) % fotos.length"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition-colors backdrop-blur-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                                    </svg>
                                </button>
                                <button @click="activa = (activa + 1) % fotos.length"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition-colors backdrop-blur-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                                    </svg>
                                </button>
                                <span class="absolute bottom-3 right-3 text-xs bg-black/50 text-white px-2 py-1 rounded-full backdrop-blur-sm"
                                      x-text="(activa + 1) + ' / ' + fotos.length"></span>
                            </div>
                        </template>

                        {{-- Badge certificado --}}
                        @if($cert)
                            <span class="absolute top-4 left-4 badge-cert text-xs px-3 py-1">✓ Certificado AMM</span>
                        @else
                            <span class="absolute top-4 left-4 badge-nocert text-xs px-3 py-1">Sin certificar</span>
                        @endif
                    </div>

                    {{-- Thumbnails --}}
                    <template x-if="fotos.length > 1">
                        <div class="flex gap-2 overflow-x-auto pb-1">
                            <template x-for="(foto, i) in fotos" :key="i">
                                <button @click="activa = i"
                                        :class="activa === i ? 'ring-2 ring-brand-orange ring-offset-2 ring-offset-page' : 'opacity-60 hover:opacity-100'"
                                        class="shrink-0 w-16 h-16 rounded-lg overflow-hidden transition-all">
                                    <img :src="foto" class="w-full h-full object-cover">
                                </button>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- ESPECIFICACIONES --}}
                <div class="bg-card border border-base rounded-2xl p-6">
                    <h2 class="font-semibold text-base mb-4">Especificaciones</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach([
                            ['Año',          $vehiculo->anio],
                            ['Marca',        $vehiculo->marca],
                            ['Modelo',       $vehiculo->modelo],
                            ['Versión',      $vehiculo->version ?? '—'],
                            ['Kilometraje',  $vehiculo->kilometraje_formateado],
                            ['Transmisión',  ucfirst($vehiculo->transmision)],
                            ['Combustible',  ucfirst($vehiculo->combustible)],
                            ['Color',        ucfirst($vehiculo->color)],
                            ['Puertas',      $vehiculo->puertas ?? '—'],
                            ['Cilindros',    $vehiculo->cilindros ?? '—'],
                            ['Motor',        $vehiculo->motor ?? '—'],
                            ['Ciudad',       $vehiculo->ciudad ?? '—'],
                        ] as [$label, $valor])
                            <div class="bg-card2 rounded-xl p-3">
                                <p class="text-xs text-muted mb-0.5">{{ $label }}</p>
                                <p class="text-sm font-medium text-base">{{ $valor }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- DESCRIPCIÓN --}}
                @if($vehiculo->descripcion)
                    <div class="bg-card border border-base rounded-2xl p-6">
                        <h2 class="font-semibold text-base mb-3">Descripción</h2>
                        <p class="text-sm text-muted leading-relaxed whitespace-pre-line">{{ $vehiculo->descripcion }}</p>
                    </div>
                @endif

                {{-- CERTIFICACIÓN --}}
                @if($cert)
                    <div class="bg-card border border-[#2ecc71]/30 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-[#0a2010] border border-[#2ecc71]/30 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[#2ecc71]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <h2 class="font-semibold text-[#2ecc71]">✓ Certificado AutosMotosYMás</h2>
                                    @if($cert->puntaje)
                                        <span class="text-sm font-bold text-[#2ecc71]">{{ $cert->puntaje }}/100 pts</span>
                                    @endif
                                </div>
                                <p class="text-sm text-muted mt-1">
                                    Inspeccionado el {{ $cert->fecha_inspeccion->format('d/m/Y') }}
                                    @if($cert->verificador)
                                        · por <span class="text-base">{{ $cert->verificador->nombre ?? 'taller aliado' }}</span>
                                    @endif
                                </p>
                                @if($cert->observaciones)
                                    <p class="text-sm text-muted mt-2 leading-relaxed">{{ $cert->observaciones }}</p>
                                @endif
                                @if($cert->reporte_pdf)
                                    <a href="{{ asset('storage/' . $cert->reporte_pdf) }}"
                                       target="_blank"
                                       class="inline-flex items-center gap-1.5 text-xs text-[#2ecc71] hover:underline mt-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                        </svg>
                                        Ver reporte de inspección
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- AGENCIA (móvil — se oculta en desktop donde aparece en sidebar) --}}
                <div class="lg:hidden">
                    @include('publico.vehiculo._agencia-card')
                </div>

            </div>{{-- fin columna izquierda --}}

            {{-- ── COLUMNA DERECHA (sticky) ── --}}
            <div class="space-y-4 lg:sticky lg:top-20">

                {{-- Precio y CTA principal --}}
                <div class="bg-card border border-base rounded-2xl p-6">
                    <div class="flex items-start justify-between gap-3 mb-1">
                        <div>
                            <h1 class="text-lg font-bold text-base leading-tight">{{ $titulo }}</h1>
                            @if($vehiculo->version)
                                <p class="text-xs text-muted mt-0.5">{{ $vehiculo->version }}</p>
                            @endif
                        </div>
                        @if($vehiculo->destacado)
                            <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-yellow-400/20 text-yellow-400 border border-yellow-400/40">Destacado</span>
                        @endif
                    </div>

                    <p class="text-3xl font-black text-brand-orange mt-3">
                        {{ $vehiculo->precio_formateado }}
                        <span class="text-sm font-normal text-muted">MXN</span>
                    </p>
                    @if($vehiculo->precio_negociable)
                        <p class="text-xs text-muted mt-0.5">Precio negociable</p>
                    @endif

                    {{-- Specs rápidos --}}
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-muted mt-4 pt-4 border-t border-base">
                        <span>{{ $vehiculo->kilometraje_formateado }}</span>
                        <span>·</span>
                        <span class="capitalize">{{ $vehiculo->transmision }}</span>
                        <span>·</span>
                        <span class="capitalize">{{ $vehiculo->combustible }}</span>
                        @if($vehiculo->ciudad)
                            <span>·</span>
                            <span>{{ $vehiculo->ciudad }}</span>
                        @endif
                    </div>

                    {{-- Alerta lead enviado --}}
                    @if(session('lead_enviado'))
                        <div class="mt-4 bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-xl px-4 py-3">
                            ¡Mensaje enviado! La agencia te contactará pronto.
                        </div>
                    @endif

                    {{-- Formulario de contacto --}}
                    <div x-data="{ abierto: {{ session('lead_enviado') ? 'false' : 'true' }} }" class="mt-4">
                        <button @click="abierto = !abierto"
                                x-show="!abierto"
                                class="btn-primary w-full justify-center">
                            Contactar a la agencia
                        </button>

                        <form x-show="abierto"
                              x-transition
                              method="POST"
                              action="{{ route('lead.store') }}"
                              class="space-y-3">
                            @csrf
                            <input type="hidden" name="vehiculo_id" value="{{ $vehiculo->id }}">

                            <div>
                                <label class="block text-xs font-medium text-base mb-1">Nombre *</label>
                                <input type="text" name="nombre"
                                       value="{{ old('nombre', auth()->user()?->name) }}"
                                       required
                                       class="w-full bg-card2 border border-base text-base text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-500"
                                       placeholder="Tu nombre completo">
                                @error('nombre') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-base mb-1">Teléfono *</label>
                                <input type="tel" name="telefono"
                                       value="{{ old('telefono') }}"
                                       required
                                       class="w-full bg-card2 border border-base text-base text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-500"
                                       placeholder="33 1234 5678">
                                @error('telefono') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-base mb-1">¿Cómo prefieres que te contacten?</label>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(['whatsapp' => 'WhatsApp', 'llamada' => 'Llamada', 'email' => 'Email', 'visita' => 'Visita'] as $val => $label)
                                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg border border-base hover:border-brand-orange/40 transition-colors has-[:checked]:border-brand-orange has-[:checked]:bg-brand-orange/5">
                                            <input type="radio" name="tipo" value="{{ $val }}"
                                                   {{ old('tipo', 'whatsapp') === $val ? 'checked' : '' }}
                                                   class="accent-brand-orange">
                                            <span class="text-xs text-base">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-base mb-1">Mensaje (opcional)</label>
                                <textarea name="mensaje" rows="3"
                                          class="w-full bg-card2 border border-base text-base text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-500 resize-none"
                                          placeholder="¿Tienes alguna pregunta sobre el vehículo?">{{ old('mensaje') }}</textarea>
                            </div>

                            <button type="submit" class="btn-primary w-full justify-center">
                                Enviar mensaje
                            </button>
                            <button type="button" @click="abierto = false"
                                    class="btn-ghost w-full justify-center text-xs">
                                Cancelar
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Agencia (desktop) --}}
                <div class="hidden lg:block">
                    @include('publico.vehiculo._agencia-card')
                </div>

                {{-- Compartir --}}
                <div class="bg-card border border-base rounded-2xl p-4">
                    <p class="text-xs font-semibold text-base mb-3">Compartir</p>
                    <div class="flex gap-2">
                        <a href="https://wa.me/?text={{ urlencode($titulo . ' — ' . url()->current()) }}"
                           target="_blank" rel="noopener"
                           class="btn-ghost flex-1 justify-center text-xs gap-1.5">
                            <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            WhatsApp
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href).then(() => alert('¡Enlace copiado!'))"
                                class="btn-ghost flex-1 justify-center text-xs gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                            </svg>
                            Copiar link
                        </button>
                    </div>
                </div>

            </div>{{-- fin columna derecha --}}
        </div>

        {{-- ══════════════ RELACIONADOS ══════════════ --}}
        @if($relacionados->isNotEmpty())
            <div class="mt-14">
                <h2 class="text-xl font-bold text-base mb-6">También te puede interesar</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($relacionados as $v)
                        <x-tarjeta-vehiculo :vehiculo="$v" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>{{-- fin container --}}
</div>

</x-app-layout>
