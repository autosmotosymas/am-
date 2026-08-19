<x-admin-layout :title="'Suscripción — ' . ($sus->agencia?->nombre ?? 'Sin agencia')">
<div class="max-w-2xl space-y-5">

    {{-- Estado general --}}
    <div class="bg-card border border-base rounded-2xl p-6">
        @php
            $sc = ['activa'=>'bg-green-500/10 text-green-400 border-green-500/20','prueba'=>'bg-blue-500/10 text-blue-400 border-blue-500/20','vencida'=>'bg-yellow-500/10 text-yellow-400 border-yellow-500/20','cancelada'=>'bg-gray-500/10 text-gray-400 border-gray-500/20'][$sus->status] ?? 'bg-card2 text-muted border-base';
        @endphp
        <div class="flex items-start justify-between gap-4 mb-5">
            <div>
                <h2 class="text-xl font-bold text-base">{{ $sus->agencia?->nombre ?? '—' }}</h2>
                <p class="text-sm text-muted mt-0.5">{{ $sus->agencia?->ciudad }}, {{ $sus->agencia?->estado }}</p>
            </div>
            <span class="shrink-0 text-sm px-3 py-1 rounded-full border capitalize {{ $sc }}">{{ $sus->status }}</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
            @foreach([
                'Plan'          => $sus->plan?->nombre . ' (' . $sus->plan?->duracion_label . ')',
                'Inicio'        => $sus->fecha_inicio?->format('d/m/Y'),
                'Vencimiento'   => $sus->fecha_vencimiento?->format('d/m/Y'),
                'Monto pagado'  => '$' . number_format($sus->precio_pagado, 2) . ' MXN',
                'Stripe Sub ID' => $sus->stripe_subscription_id ?? '—',
                'Stripe Session'=> $sus->stripe_session_id ?? '—',
            ] as $label => $valor)
                <div class="bg-card2 rounded-xl p-3">
                    <p class="text-xs text-muted mb-0.5">{{ $label }}</p>
                    <p class="text-base font-medium break-all">{{ $valor ?? '—' }}</p>
                </div>
            @endforeach
        </div>

        <div class="flex gap-2 mt-5 pt-5 border-t border-base">
            <a href="{{ route('admin.suscripciones.edit', $sus) }}" class="btn-primary text-sm">Editar</a>
            @if($sus->status === 'activa')
                <form method="POST" action="{{ route('admin.suscripciones.cancelar', $sus) }}"
                      onsubmit="return confirm('¿Cancelar esta suscripción?')">
                    @csrf @method('PATCH')
                    <button class="btn-outline text-sm text-red-400 border-red-500/20">Cancelar suscripción</button>
                </form>
            @endif
            <a href="{{ route('admin.agencias.show', $sus->agencia) }}" class="btn-ghost text-sm">Ver agencia →</a>
        </div>
    </div>

</div>
</x-admin-layout>
