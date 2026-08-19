<x-agencia-layout title="Certificaciones">

    <div class="flex justify-end mb-5">
        <a href="{{ route('agencia.certificaciones.create') }}" class="btn-primary text-sm">+ Solicitar certificación</a>
    </div>

    <div class="space-y-3">
        @forelse($certificaciones as $cert)
            <div class="bg-card border border-base rounded-2xl p-5">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-sm font-semibold text-base">
                                {{ $cert->vehiculo?->anio }} {{ $cert->vehiculo?->marca }} {{ $cert->vehiculo?->modelo }}
                            </span>
                            @php
                                $rc = ['pendiente'=>['text-yellow-400','bg-yellow-500/10','border-yellow-500/20'],
                                       'aprobado' =>['text-green-400', 'bg-green-500/10', 'border-green-500/20'],
                                       'rechazado'=>['text-red-400',   'bg-red-500/10',   'border-red-500/20']];
                                [$tc,$bc,$brc] = $rc[$cert->resultado] ?? ['text-muted','bg-card2','border-base'];
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full border {{ $tc }} {{ $bc }} {{ $brc }} capitalize">
                                {{ $cert->resultado }}
                            </span>
                        </div>
                        <p class="text-xs text-muted">
                            Verificador: {{ $cert->verificador?->nombre ?? 'Por asignar' }}
                            · {{ $cert->fecha_inspeccion->format('d/m/Y') }}
                            @if($cert->puntaje) · {{ $cert->puntaje }}/100 pts @endif
                        </p>
                        @if($cert->observaciones)
                            <p class="text-xs text-muted mt-1 truncate">{{ $cert->observaciones }}</p>
                        @endif
                    </div>
                    @if($cert->resultado === 'aprobado')
                        <span class="text-xs text-green-400 font-semibold shrink-0">✓ Certificado</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-card border border-base rounded-2xl text-center py-16">
                <p class="text-base text-muted">Sin solicitudes de certificación aún.</p>
                <a href="{{ route('agencia.certificaciones.create') }}" class="btn-primary text-sm mt-4 inline-block">Solicitar primera certificación</a>
            </div>
        @endforelse
    </div>

    @if($certificaciones->hasPages())
        <div class="mt-5">{{ $certificaciones->links('components.paginacion') }}</div>
    @endif

</x-agencia-layout>
