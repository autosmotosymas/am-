<x-admin-layout title="Certificaciones">

    <div class="flex flex-wrap gap-2 justify-between mb-5">
        <div class="flex gap-2">
            @foreach(['' => 'Todas', 'pendiente' => 'Pendientes', 'aprobado' => 'Aprobadas', 'rechazado' => 'Rechazadas'] as $val => $label)
                <a href="{{ route('admin.certificaciones.index', $val ? ['resultado' => $val] : []) }}"
                   class="px-4 py-2 text-sm rounded-xl border transition-colors
                          {{ request('resultado', '') === $val
                              ? 'bg-brand-orange text-white border-brand-orange'
                              : 'bg-card border-base text-muted hover:border-brand-orange/40' }}">
                    {{ $label }}
                    <span class="ml-1 text-xs opacity-70">{{ $conteos[$val] ?? array_sum($conteos) }}</span>
                </a>
            @endforeach
        </div>
        <a href="{{ route('admin.certificaciones.create') }}" class="btn-primary text-sm">+ Nueva</a>
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
                            Agencia: {{ $cert->vehiculo?->agencia?->nombre ?? '—' }}
                            · Verificador: {{ $cert->verificador?->nombre ?? '—' }}
                            · {{ $cert->fecha_inspeccion->format('d/m/Y') }}
                            @if($cert->puntaje) · {{ $cert->puntaje }}/100 pts @endif
                        </p>
                        @if($cert->observaciones)
                            <p class="text-xs text-muted mt-1 truncate">{{ $cert->observaciones }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        @if($cert->resultado === 'pendiente')
                            <form method="POST" action="{{ route('admin.certificaciones.aprobar', $cert) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs px-3 py-1.5 rounded-lg bg-green-500/10 text-green-400 border border-green-500/20 hover:bg-green-500/20 transition-colors">
                                    Aprobar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.certificaciones.rechazar', $cert) }}"
                                  x-data="{ obs: '' }"
                                  @submit.prevent="
                                      if (!obs) { alert('Escribe una observación'); return; }
                                      $el.querySelector('[name=observaciones]').value = obs;
                                      $el.submit();
                                  ">
                                @csrf @method('PATCH')
                                <input type="hidden" name="observaciones">
                                <button type="button"
                                        @click="obs = prompt('Motivo del rechazo:'); if(obs) $el.requestSubmit()"
                                        class="text-xs px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition-colors">
                                    Rechazar
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.certificaciones.edit', $cert) }}"
                           class="text-xs text-brand-orange hover:underline">Editar</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-card border border-base rounded-2xl text-center py-16">
                <p class="text-base text-muted">Sin certificaciones en esta categoría.</p>
            </div>
        @endforelse
    </div>

    @if($certificaciones->hasPages())
        <div class="mt-5">{{ $certificaciones->links('components.paginacion') }}</div>
    @endif

</x-admin-layout>
