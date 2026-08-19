<x-agencia-layout title="Suscripción">

    {{-- Estado actual --}}
    @if($suscripcion)
        <div class="mb-6 bg-green-500/10 border border-green-500/20 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex-1">
                <p class="text-base font-semibold text-green-400">
                    Plan activo: {{ $suscripcion->plan?->nombre }}
                    <span class="font-normal text-green-400/70">— {{ $suscripcion->plan?->duracion_label }}</span>
                </p>
                <p class="text-xs text-muted mt-0.5">Vence el {{ $suscripcion->fecha_vencimiento->format('d \d\e F \d\e Y') }}</p>
            </div>
            <span class="text-xs px-3 py-1.5 rounded-xl bg-green-500/10 text-green-400 border border-green-500/20 shrink-0">
                ✓ Activa
            </span>
        </div>
    @else
        <div class="mb-6 bg-yellow-500/10 border border-yellow-500/20 rounded-2xl p-5">
            <p class="text-base font-semibold text-yellow-400">Sin suscripción activa</p>
            <p class="text-xs text-muted mt-0.5">Elige un plan y duración para publicar tu inventario.</p>
        </div>
    @endif

    {{-- Selector de planes con Alpine.js --}}
    <div x-data="{
        duracion: '{{ $suscripcion?->plan?->duracion ?? 'trimestral' }}',
        planes: @json($planesJson),
        planActualId: @json($suscripcion?->plan_id),
        mesesMap: { trimestral: 3, semestral: 6, anual: 12 },
        getPlan(grupo) { return this.planes[grupo]?.[this.duracion] ?? null; },
        esCurrent(grupo) {
            const p = this.getPlan(grupo);
            return p && p.id == this.planActualId;
        },
        getPrecio(grupo) {
            const p = this.getPlan(grupo);
            if (!p) return '';
            return '$' + new Intl.NumberFormat('es-MX').format(p.precio);
        },
        getPrecioMensual(grupo) {
            const p = this.getPlan(grupo);
            if (!p) return '';
            const meses = this.mesesMap[this.duracion] ?? 1;
            return '$' + new Intl.NumberFormat('es-MX').format(Math.round(p.precio / meses));
        },
        getPlanId(grupo) {
            const p = this.getPlan(grupo);
            return p ? p.id : '';
        }
    }">

        {{-- Toggle duración --}}
        <div class="flex bg-card2 border border-base rounded-2xl p-1 gap-1 mb-6">
            <template x-for="(label, key) in { trimestral: 'Trimestral (3 meses)', semestral: 'Semestral (6 meses)', anual: 'Anual (12 meses)' }" :key="key">
                <button
                    @click="duracion = key"
                    :class="duracion === key
                        ? 'bg-brand-orange text-white shadow-sm'
                        : 'text-muted hover:text-base'"
                    class="flex-1 py-2.5 px-2 rounded-xl text-xs sm:text-sm font-semibold transition-all text-center">
                    <span x-text="label"></span>
                </button>
            </template>
        </div>

        {{-- Cards de planes --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            @foreach(['base', 'medio', 'premium'] as $grupo)
            @php
                $datosTrimestral = $planesJson[$grupo]['trimestral'] ?? null;
                $nombrePlan      = ucfirst($grupo);
                $esMedio         = $grupo === 'medio';
            @endphp

            <div class="bg-card border {{ $esMedio ? 'border-brand-orange/40' : 'border-base' }} rounded-2xl p-6 flex flex-col relative">

                @if($esMedio)
                    <span class="absolute -top-3 left-6 text-xs px-3 py-1 rounded-full bg-brand-orange text-white font-semibold">
                        Más popular
                    </span>
                @endif

                {{-- Nombre del plan --}}
                <div class="mb-5">
                    <h2 class="text-lg font-bold text-base">{{ $nombrePlan }}</h2>

                    {{-- Precio total del período --}}
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-black text-base" x-text="getPrecio('{{ $grupo }}')"></span>
                        <span class="text-sm text-muted">total</span>
                    </div>

                    {{-- Equivalente mensual --}}
                    <p class="text-xs text-muted mt-0.5">
                        aprox. <span x-text="getPrecioMensual('{{ $grupo }}')"></span>/mes
                    </p>
                </div>

                {{-- Features (los mismos para todas las duraciones del plan) --}}
                @if($datosTrimestral)
                <ul class="space-y-2 flex-1 mb-6">
                    <li class="flex items-center gap-2 text-sm text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        <strong class="text-base">{{ $datosTrimestral['max_vehiculos'] }}</strong>&nbsp;espacios de anuncios
                    </li>
                    <li class="flex items-center gap-2 text-sm text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        <strong class="text-base">{{ $datosTrimestral['destacados'] }}</strong>&nbsp;anuncios destacados
                    </li>
                    <li class="flex items-center gap-2 text-sm text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        <strong class="text-base">{{ $datosTrimestral['videos'] }}</strong>&nbsp;videos en tus anuncios
                    </li>
                    <li class="flex items-center gap-2 text-sm text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        Banner de certificado
                    </li>
                    @if(is_array($datosTrimestral['features']))
                        @foreach(array_slice($datosTrimestral['features'], 4) as $feature)
                            <li class="flex items-center gap-2 text-sm text-muted">
                                <span class="text-brand-orange shrink-0">✓</span>
                                {{ $feature }}
                            </li>
                        @endforeach
                    @endif
                </ul>
                @endif

                {{-- Botón acción --}}
                <div x-show="esCurrent('{{ $grupo }}')">
                    <div class="w-full text-center py-2.5 rounded-xl border border-green-500/30 text-green-400 text-sm font-medium">
                        Plan actual ✓
                    </div>
                </div>

                <div x-show="!esCurrent('{{ $grupo }}')">
                    <form method="POST" action="{{ route('agencia.suscripcion.checkout') }}">
                        @csrf
                        <input type="hidden" name="plan_id" :value="getPlanId('{{ $grupo }}')">
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl text-sm font-semibold transition-colors
                                       {{ $esMedio
                                           ? 'bg-brand-orange text-white hover:bg-brand-orange/90'
                                           : 'bg-card2 border border-base text-base hover:border-brand-orange/40' }}">
                            {{ $suscripcion ? 'Cambiar a este plan' : 'Suscribirme' }} →
                        </button>
                    </form>
                </div>

            </div>
            @endforeach

        </div>

        {{-- Nota de ahorro --}}
        <p class="text-xs text-muted text-center mt-4">
            El plan anual incluye el mejor precio por mes. Todos los planes incluyen banner de certificado.
        </p>

    </div>

    {{-- Historial de suscripciones --}}
    @if($agencia && $agencia->suscripciones->isNotEmpty())
        <div class="mt-8 bg-card border border-base rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-base">
                <h3 class="font-semibold text-base">Historial de suscripciones</h3>
            </div>
            <div class="divide-y divide-base">
                @foreach($agencia->suscripciones->take(6) as $sus)
                    <div class="px-5 py-3 flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-medium">
                                {{ $sus->plan?->nombre }}
                                @if($sus->plan?->duracion_label)
                                    <span class="text-xs text-muted font-normal">— {{ $sus->plan->duracion_label }}</span>
                                @endif
                            </p>
                            <p class="text-xs text-muted">
                                {{ $sus->fecha_inicio->format('d/m/Y') }} — {{ $sus->fecha_vencimiento->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="text-xs capitalize px-2 py-0.5 rounded-full
                            {{ $sus->status === 'activa' ? 'bg-green-500/10 text-green-400' : 'bg-gray-500/10 text-gray-400' }}">
                            {{ $sus->status }}
                        </span>
                        <span class="text-sm text-muted shrink-0">${{ number_format($sus->precio_pagado, 0) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @error('plan_id')
        <p class="mt-3 text-xs text-red-400">{{ $message }}</p>
    @enderror
    @if(session('error'))
        <div class="mt-4 bg-red-500/10 border border-red-500/20 text-red-400 text-base rounded-xl px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

</x-agencia-layout>
