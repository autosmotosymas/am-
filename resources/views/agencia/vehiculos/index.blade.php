<x-agencia-layout title="Inventario">

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-base text-muted">
                {{ $vehiculos->total() }} {{ Str::plural('vehículo', $vehiculos->total()) }}
                @if($plan)
                    · límite {{ $vehiculos->total() }} / {{ $plan->max_vehiculos }}
                @endif
            </p>
        </div>
        <a href="{{ route('agencia.vehiculos.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Agregar vehículo
        </a>
    </div>

    @if($vehiculos->isEmpty())
        <div class="bg-card border border-base rounded-2xl text-center py-20">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
            </svg>
            <p class="font-semibold text-base mb-1">Sin vehículos aún</p>
            <p class="text-base text-muted mb-6">Publica tu primer vehículo para aparecer en el catálogo.</p>
            <a href="{{ route('agencia.vehiculos.create') }}" class="btn-primary">Publicar ahora</a>
        </div>
    @else
        <div class="bg-card border border-base rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-base text-xs text-muted uppercase tracking-wide">
                        <th class="text-left px-4 py-3">Vehículo</th>
                        <th class="text-right px-4 py-3 hidden sm:table-cell">Precio</th>
                        <th class="text-center px-4 py-3 hidden md:table-cell">Vistas</th>
                        <th class="text-center px-4 py-3 hidden md:table-cell">Leads</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base">
                    @foreach($vehiculos as $v)
                        <tr class="hover:bg-card2 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-10 rounded-lg bg-card2 overflow-hidden shrink-0">
                                        @if($v->fotoPrincipal)
                                            <img src="{{ $v->fotoPrincipal->url }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-base truncate">{{ $v->anio }} {{ $v->marca }} {{ $v->modelo }}</p>
                                        <p class="text-xs text-muted truncate">{{ $v->version ?? $v->color }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right hidden sm:table-cell font-semibold text-brand-orange">
                                {{ $v->precio_formateado }}
                            </td>
                            <td class="px-4 py-3 text-center hidden md:table-cell text-muted">
                                {{ number_format($v->vistas) }}
                            </td>
                            <td class="px-4 py-3 text-center hidden md:table-cell text-muted">
                                {{ $v->leads_count ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $statusMap = [
                                        'disponible' => ['label' => 'Activo',   'class' => 'bg-green-500/10 text-green-400 border-green-500/20'],
                                        'inactivo'   => ['label' => 'Pausado',  'class' => 'bg-gray-500/10 text-gray-400 border-gray-500/20'],
                                        'apartado'   => ['label' => 'Apartado', 'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                                        'vendido'    => ['label' => 'Vendido',  'class' => 'bg-red-500/10 text-red-400 border-red-500/20'],
                                    ];
                                    $st = $statusMap[$v->status] ?? ['label' => $v->status, 'class' => ''];
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full border {{ $st['class'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1 justify-end">
                                    <a href="{{ route('vehiculo.show', $v) }}" target="_blank"
                                       class="btn-ghost w-8 h-8 p-0" title="Ver en sitio">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('agencia.vehiculos.edit', $v) }}"
                                       class="btn-ghost w-8 h-8 p-0" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('agencia.vehiculos.destroy', $v) }}"
                                          onsubmit="return confirm('¿Eliminar este vehículo? No se puede deshacer.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-ghost w-8 h-8 p-0 text-red-400 hover:bg-red-500/10" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($vehiculos->hasPages())
            <div class="mt-5">
                {{ $vehiculos->links('components.paginacion') }}
            </div>
        @endif
    @endif

</x-agencia-layout>
