<x-admin-layout :title="$verificador->nombre">

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-6">

        {{-- Info --}}
        <div class="space-y-5">
            <div class="bg-card border border-base rounded-2xl p-6">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-base">{{ $verificador->nombre }}</h2>
                        @if($verificador->zona)
                            <p class="text-sm text-muted">{{ $verificador->zona }}</p>
                        @endif
                    </div>
                    <span class="shrink-0 text-sm px-3 py-1 rounded-full border
                        {{ $verificador->activo ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">
                        {{ $verificador->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-card2 rounded-xl p-3">
                        <p class="text-xs text-muted mb-0.5">Email</p>
                        <p class="text-base font-medium">{{ $verificador->email }}</p>
                    </div>
                    @if($verificador->telefono)
                        <div class="bg-card2 rounded-xl p-3">
                            <p class="text-xs text-muted mb-0.5">Teléfono</p>
                            <p class="text-base font-medium">{{ $verificador->telefono }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex gap-2 mt-5 pt-5 border-t border-base">
                    <a href="{{ route('admin.verificadores.edit', $verificador) }}" class="btn-primary text-sm">Editar</a>
                </div>
            </div>

            {{-- Historial de certificaciones --}}
            <div class="bg-card border border-base rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-base flex items-center justify-between">
                    <h3 class="font-semibold text-base">Certificaciones realizadas</h3>
                    <span class="text-sm text-muted">{{ $verificador->certificaciones->count() }} total</span>
                </div>

                @if($verificador->certificaciones->isEmpty())
                    <p class="text-base text-muted text-center py-10">Sin certificaciones registradas.</p>
                @else
                    <div class="divide-y divide-base">
                        @foreach($verificador->certificaciones as $cert)
                            @php
                                $rc = ['pendiente'=>['text-yellow-400','bg-yellow-500/10','border-yellow-500/20'],
                                       'aprobado' =>['text-green-400', 'bg-green-500/10', 'border-green-500/20'],
                                       'rechazado'=>['text-red-400',   'bg-red-500/10',   'border-red-500/20']];
                                [$tc,$bc,$brc] = $rc[$cert->resultado] ?? ['text-muted','bg-card2','border-base'];
                            @endphp
                            <div class="px-5 py-3.5 flex items-center gap-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-base truncate">
                                        {{ $cert->vehiculo?->anio }} {{ $cert->vehiculo?->marca }} {{ $cert->vehiculo?->modelo }}
                                    </p>
                                    <p class="text-xs text-muted">
                                        {{ $cert->vehiculo?->agencia?->nombre ?? '—' }}
                                        · {{ $cert->fecha_inspeccion->format('d/m/Y') }}
                                        @if($cert->puntaje) · {{ $cert->puntaje }}/100 pts @endif
                                    </p>
                                </div>
                                <span class="text-xs px-2 py-0.5 rounded-full border {{ $tc }} {{ $bc }} {{ $brc }} capitalize shrink-0">
                                    {{ $cert->resultado }}
                                </span>
                                <a href="{{ route('admin.certificaciones.edit', $cert) }}"
                                   class="text-xs text-brand-orange hover:underline shrink-0">Ver</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Stats sidebar --}}
        <div class="space-y-4">
            @php
                $total    = $verificador->certificaciones->count();
                $aprobados = $verificador->certificaciones->where('resultado', 'aprobado')->count();
                $tasa = $total > 0 ? round($aprobados / $total * 100) : 0;
            @endphp
            <div class="bg-card border border-base rounded-2xl p-5 space-y-4">
                <h3 class="font-semibold text-base">Estadísticas</h3>

                <div class="text-center">
                    <p class="text-4xl font-black text-brand-orange">{{ $total }}</p>
                    <p class="text-xs text-muted mt-1">certificaciones totales</p>
                </div>

                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="bg-card2 rounded-xl p-2">
                        <p class="text-green-400 font-bold text-lg">{{ $aprobados }}</p>
                        <p class="text-muted">aprobadas</p>
                    </div>
                    <div class="bg-card2 rounded-xl p-2">
                        <p class="text-red-400 font-bold text-lg">{{ $verificador->certificaciones->where('resultado', 'rechazado')->count() }}</p>
                        <p class="text-muted">rechazadas</p>
                    </div>
                    <div class="bg-card2 rounded-xl p-2">
                        <p class="text-yellow-400 font-bold text-lg">{{ $verificador->certificaciones->where('resultado', 'pendiente')->count() }}</p>
                        <p class="text-muted">pendientes</p>
                    </div>
                </div>

                @if($total > 0)
                    <div>
                        <div class="flex justify-between text-xs text-muted mb-1">
                            <span>Tasa de aprobación</span>
                            <span>{{ $tasa }}%</span>
                        </div>
                        <div class="h-2 bg-card2 rounded-full overflow-hidden">
                            <div class="h-full bg-green-400 rounded-full" style="width: {{ $tasa }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-admin-layout>
