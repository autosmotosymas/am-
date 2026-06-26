<x-agencia-layout title="Suscripción">

    {{-- Estado actual --}}
    @if($suscripcion)
        <div class="mb-6 bg-green-500/10 border border-green-500/20 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex-1">
                <p class="text-base font-semibold text-green-400">Plan activo: {{ $suscripcion->plan?->nombre }}</p>
                <p class="text-xs text-muted mt-0.5">Vence el {{ $suscripcion->fecha_vencimiento->format('d \d\e F \d\e Y') }}</p>
            </div>
            <span class="text-xs px-3 py-1.5 rounded-xl bg-green-500/10 text-green-400 border border-green-500/20 shrink-0">
                ✓ Activa
            </span>
        </div>
    @else
        <div class="mb-6 bg-yellow-500/10 border border-yellow-500/20 rounded-2xl p-5">
            <p class="text-base font-semibold text-yellow-400">Sin suscripción activa</p>
            <p class="text-xs text-muted mt-0.5">Elige un plan para publicar tu inventario.</p>
        </div>
    @endif

    {{-- Planes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($planes as $plan)
            @php $esCurrent = $suscripcion?->plan_id === $plan->id; @endphp
            <div class="bg-card border {{ $plan->badge_premium ? 'border-brand-orange/40' : 'border-base' }} rounded-2xl p-6 flex flex-col relative">

                @if($plan->badge_premium)
                    <span class="absolute -top-3 left-6 text-xs px-3 py-1 rounded-full bg-brand-orange text-white font-semibold">
                        Más popular
                    </span>
                @endif

                <div class="mb-5">
                    <h2 class="text-lg font-bold text-base">{{ $plan->nombre }}</h2>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-black text-base">{{ $plan->precio_formateado }}</span>
                        <span class="text-sm text-muted">/mes</span>
                    </div>
                </div>

                <ul class="space-y-2 flex-1 mb-6">
                    <li class="flex items-center gap-2 text-base text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        Hasta <strong class="text-base">{{ $plan->max_vehiculos }}</strong> vehículos
                    </li>
                    <li class="flex items-center gap-2 text-base text-muted">
                        <span class="text-brand-orange shrink-0">✓</span>
                        <strong class="text-base">{{ $plan->max_fotos_por_vehiculo }}</strong> fotos por vehículo
                    </li>
                    @if($plan->vehiculos_destacados > 0)
                        <li class="flex items-center gap-2 text-base text-muted">
                            <span class="text-brand-orange shrink-0">✓</span>
                            <strong class="text-base">{{ $plan->vehiculos_destacados }}</strong> destacados al mes
                        </li>
                    @endif
                    @if($plan->incluye_certificacion)
                        <li class="flex items-center gap-2 text-base text-muted">
                            <span class="text-brand-orange shrink-0">✓</span>
                            Certificaciones incluidas
                        </li>
                    @endif
                    @if(is_array($plan->features))
                        @foreach($plan->features as $feature)
                            <li class="flex items-center gap-2 text-base text-muted">
                                <span class="text-brand-orange shrink-0">✓</span>
                                {{ $feature }}
                            </li>
                        @endforeach
                    @endif
                </ul>

                @if($esCurrent)
                    <div class="w-full text-center py-2.5 rounded-xl border border-green-500/30 text-green-400 text-sm font-medium">
                        Plan actual
                    </div>
                @else
                    <form method="POST" action="{{ route('agencia.suscripcion.checkout') }}">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl text-sm font-semibold transition-colors
                                       {{ $plan->badge_premium
                                           ? 'bg-brand-orange text-white hover:bg-brand-orange/90'
                                           : 'bg-card2 border border-base text-base hover:border-brand-orange/40' }}">
                            {{ $suscripcion ? 'Cambiar a este plan' : 'Suscribirme' }} →
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Historial de pagos --}}
    @if($agencia && $agencia->suscripciones->isNotEmpty())
        <div class="mt-8 bg-card border border-base rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-base">
                <h3 class="font-semibold text-base">Historial de suscripciones</h3>
            </div>
            <div class="divide-y divide-base">
                @foreach($agencia->suscripciones->take(6) as $sus)
                    <div class="px-5 py-3 flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-medium">{{ $sus->plan?->nombre }}</p>
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
