@props(['vehiculo'])

@php
    $foto = $vehiculo->fotoPrincipal;
    $certificado = $vehiculo->certificacion !== null;
@endphp

<a href="{{ route('vehiculo.show', $vehiculo) }}"
   class="group bg-card border border-base rounded-2xl overflow-hidden flex flex-col hover:border-brand-orange/40 hover:shadow-lg hover:shadow-brand-orange/5 transition-all duration-200">

    {{-- Foto --}}
    <div class="relative aspect-[4/3] bg-card2 overflow-hidden">
        @if($foto)
            <img src="{{ $foto->url }}"
                 alt="{{ $vehiculo->marca }} {{ $vehiculo->modelo }} {{ $vehiculo->anio }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-out"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                </svg>
            </div>
        @endif

        {{-- Badge certificado --}}
        @if($certificado)
            <span class="absolute top-3 left-3 badge-cert">✓ Certificado</span>
        @else
            <span class="absolute top-3 left-3 badge-nocert">Sin certificar</span>
        @endif

        {{-- Badge destacado --}}
        @if($vehiculo->destacado)
            <span class="absolute top-3 right-3 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-yellow-400/20 text-yellow-400 border border-yellow-400/40">
                Destacado
            </span>
        @endif
    </div>

    {{-- Info --}}
    <div class="p-4 flex flex-col flex-1">
        <div class="flex items-start justify-between gap-2 mb-1">
            <h3 class="font-semibold text-base leading-tight group-hover:text-brand-orange transition-colors">
                {{ $vehiculo->anio }} {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
            </h3>
        </div>

        @if($vehiculo->version)
            <p class="text-xs text-muted mb-2">{{ $vehiculo->version }}</p>
        @endif

        <p class="text-xl font-bold text-brand-orange mt-auto mb-3">
            {{ $vehiculo->precio_formateado }}
            @if($vehiculo->precio_negociable)
                <span class="text-xs font-normal text-muted">negociable</span>
            @endif
        </p>

        {{-- Specs --}}
        <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-muted border-t border-base pt-3">
            <span>{{ number_format($vehiculo->kilometraje, 0, '.', ',') }} km</span>
            <span>·</span>
            <span class="capitalize">{{ $vehiculo->transmision }}</span>
            <span>·</span>
            <span class="capitalize">{{ $vehiculo->combustible }}</span>
        </div>

        {{-- Agencia --}}
        @if($vehiculo->agencia)
            <p class="text-sm text-muted mt-2 truncate">
                {{ $vehiculo->agencia->nombre }}
                @if($vehiculo->agencia->verificada)
                    <span class="text-brand-orange">✓</span>
                @endif
            </p>
        @endif
    </div>
</a>
