<x-admin-layout title="Certificación">
<div class="max-w-2xl space-y-5">

    <div class="bg-card border border-base rounded-2xl p-6">
        @php
            $rc = ['pendiente'=>['text-yellow-400','bg-yellow-500/10','border-yellow-500/20'],'aprobado'=>['text-green-400','bg-green-500/10','border-green-500/20'],'rechazado'=>['text-red-400','bg-red-500/10','border-red-500/20']];
            [$tc,$bc,$brc] = $rc[$cert->resultado] ?? ['text-muted','bg-card2','border-base'];
        @endphp

        <div class="flex items-start justify-between gap-4 mb-5">
            <div>
                <h2 class="text-xl font-bold text-base">
                    {{ $cert->vehiculo?->anio }} {{ $cert->vehiculo?->marca }} {{ $cert->vehiculo?->modelo }}
                </h2>
                <p class="text-sm text-muted mt-0.5">{{ $cert->vehiculo?->agencia?->nombre ?? '—' }}</p>
            </div>
            <span class="shrink-0 text-sm px-3 py-1 rounded-full border capitalize {{ $tc }} {{ $bc }} {{ $brc }}">
                {{ $cert->resultado }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm mb-5">
            @foreach([
                'Verificador'      => $cert->verificador?->nombre ?? '—',
                'Fecha inspección' => $cert->fecha_inspeccion?->format('d/m/Y'),
                'Puntaje'          => $cert->puntaje ? $cert->puntaje . '/100 pts' : '—',
            ] as $label => $valor)
                <div class="bg-card2 rounded-xl p-3">
                    <p class="text-xs text-muted mb-0.5">{{ $label }}</p>
                    <p class="text-base font-medium">{{ $valor }}</p>
                </div>
            @endforeach
        </div>

        @if($cert->observaciones)
            <div class="bg-card2 rounded-xl p-4 mb-5">
                <p class="text-xs text-muted mb-1">Observaciones</p>
                <p class="text-sm text-base leading-relaxed">{{ $cert->observaciones }}</p>
            </div>
        @endif

        @if($cert->reporte_pdf)
            <a href="{{ asset('storage/' . $cert->reporte_pdf) }}" target="_blank"
               class="inline-flex items-center gap-1.5 text-sm text-brand-orange hover:underline mb-5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                Ver reporte PDF
            </a>
        @endif

        <div class="flex flex-wrap gap-2 pt-5 border-t border-base">
            @if($cert->resultado === 'pendiente')
                <form method="POST" action="{{ route('admin.certificaciones.aprobar', $cert) }}">
                    @csrf @method('PATCH')
                    <button class="btn-primary text-sm">✓ Aprobar</button>
                </form>
                <form method="POST" action="{{ route('admin.certificaciones.rechazar', $cert) }}"
                      x-data="{ obs: '' }"
                      @submit.prevent="if(!obs){alert('Escribe un motivo');return;} $el.querySelector('[name=observaciones]').value=obs; $el.submit()">
                    @csrf @method('PATCH')
                    <input type="hidden" name="observaciones">
                    <button type="button"
                            @click="obs=prompt('Motivo del rechazo:'); if(obs) $el.requestSubmit()"
                            class="btn-outline text-sm text-red-400 border-red-500/20">Rechazar</button>
                </form>
            @endif
            <a href="{{ route('admin.certificaciones.edit', $cert) }}" class="btn-ghost text-sm">Editar</a>
            @if($cert->vehiculo)
                <a href="{{ route('vehiculo.show', $cert->vehiculo) }}" target="_blank" class="btn-ghost text-sm">Ver vehículo →</a>
            @endif
        </div>
    </div>

</div>
</x-admin-layout>
