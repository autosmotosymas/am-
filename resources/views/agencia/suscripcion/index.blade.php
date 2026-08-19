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

    {{-- Datos de planes fuera del atributo HTML para evitar conflictos de escapado --}}
    <script>
        window.__ammPlanes    = {!! json_encode($planesJson) !!};
        window.__ammPlanActual = {!! json_encode($suscripcion?->plan_id) !!};
    </script>

    {{-- Selector de planes con Alpine.js --}}
    <div x-data="{
        duracion: '{{ $suscripcion?->plan?->duracion ?? 'trimestral' }}',
        planes: window.__ammPlanes,
        planActualId: window.__ammPlanActual,
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
        },
        updateForms() {
            document.querySelectorAll('[data-plan-input]').forEach(input => {
                const grupo = input.dataset.planInput;
                input.value = this.getPlanId(grupo) || input.value;
            });
        }
    }" x-init="$watch('duracion', () => updateForms())">

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
                $dt         = $planesJson[$grupo]['trimestral'] ?? null; // fallback server-side
                $nombrePlan = ucfirst($grupo);
                $esMedio    = $grupo === 'medio';
                // Detectar si este grupo es el plan actual
                $esCurrent  = $suscripcion && $dt && isset($planesJson[$grupo][$suscripcion->plan?->duracion ?? ''])
                              && $planesJson[$grupo][$suscripcion->plan->duracion]['id'] == $suscripcion->plan_id;
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

                    {{-- Precio: Alpine.js actualiza, PHP como fallback visible --}}
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-black text-base"
                              x-text="getPrecio('{{ $grupo }}') || '{{ $dt ? '$'.number_format((float)$dt['precio'], 0, '.', ',') : '' }}'">
                            {{ $dt ? '$'.number_format((float)$dt['precio'], 0, '.', ',') : '' }}
                        </span>
                        <span class="text-sm text-muted">total</span>
                    </div>
                    <p class="text-xs text-muted mt-0.5">
                        aprox. <span x-text="getPrecioMensual('{{ $grupo }}') || '{{ $dt ? '$'.number_format((float)$dt['precio'] / max($dt['meses'],1), 0, '.', ',') : '' }}'">
                            {{ $dt ? '$'.number_format((float)$dt['precio'] / max($dt['meses'], 1), 0, '.', ',') : '' }}
                        </span>/mes
                    </p>
                </div>

                {{-- Features --}}
                @if($dt)
                <ul class="space-y-2 flex-1 mb-6">
                    <li class="flex items-center gap-2 text-sm text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        <strong class="text-base">{{ $dt['max_vehiculos'] }}</strong>&nbsp;espacios de anuncios
                    </li>
                    <li class="flex items-center gap-2 text-sm text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        <strong class="text-base">{{ $dt['destacados'] }}</strong>&nbsp;anuncios destacados
                    </li>
                    <li class="flex items-center gap-2 text-sm text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        <strong class="text-base">{{ $dt['videos'] }}</strong>&nbsp;videos en tus anuncios
                    </li>
                    <li class="flex items-center gap-2 text-sm text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        Banner de certificado
                    </li>
                    @if(is_array($dt['features']))
                        @foreach(array_slice($dt['features'], 4) as $feature)
                            <li class="flex items-center gap-2 text-sm text-muted">
                                <span class="text-brand-orange shrink-0">✓</span>
                                {{ $feature }}
                            </li>
                        @endforeach
                    @endif
                </ul>
                @endif

                {{-- Botón: siempre visible server-side; plan_id tiene fallback PHP --}}
                @if($esCurrent)
                    <div class="w-full text-center py-2.5 rounded-xl border border-green-500/30 text-green-400 text-sm font-medium">
                        Plan actual ✓
                    </div>
                @else
                    <form method="POST" action="{{ route('agencia.suscripcion.checkout') }}">
                        @csrf
                        {{-- data-plan-input actualizado por el watcher; value= fallback PHP (trimestral) --}}
                        <input type="hidden" name="plan_id"
                               value="{{ $dt['id'] ?? '' }}"
                               data-plan-input="{{ $grupo }}"
                               :value="getPlanId('{{ $grupo }}')">
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl text-sm font-semibold transition-colors
                                       {{ $esMedio
                                           ? 'bg-brand-orange text-white hover:bg-brand-orange/90'
                                           : 'bg-card2 border border-base text-base hover:border-brand-orange/40' }}">
                            {{ $suscripcion ? 'Cambiar a este plan' : 'Suscribirme' }} →
                        </button>
                    </form>
                @endif

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

    {{-- Botón de prueba $10 — solo si existe el plan prueba --}}
    @if(isset($planPrueba) && $planPrueba)
    <div class="fixed bottom-6 right-6 z-50">
        <form method="POST" action="{{ route('agencia.suscripcion.checkout') }}">
            @csrf
            <input type="hidden" name="plan_id" value="{{ $planPrueba->id }}">
            <button type="submit"
                    class="flex items-center gap-2 bg-gray-900 border border-white/10 text-white text-xs px-4 py-2.5 rounded-xl shadow-xl hover:bg-gray-800 transition-colors">
                🧪 Prueba $10
            </button>
        </form>
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
