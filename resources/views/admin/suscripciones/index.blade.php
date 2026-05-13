<x-admin-layout title="Suscripciones">

    <div class="flex flex-wrap gap-2 justify-between mb-5">
        <div class="flex gap-2 flex-wrap">
            @foreach(['' => 'Todas', 'activa' => 'Activas', 'prueba' => 'Prueba', 'vencida' => 'Vencidas', 'cancelada' => 'Canceladas'] as $val => $label)
                <a href="{{ route('admin.suscripciones.index', $val ? ['status' => $val] : []) }}"
                   class="px-4 py-2 text-sm rounded-xl border transition-colors
                          {{ request('status', '') === $val
                              ? 'bg-brand-orange text-white border-brand-orange'
                              : 'bg-card border-base text-muted hover:border-brand-orange/40' }}">
                    {{ $label }}
                    @if($val)
                        <span class="ml-1 text-xs opacity-70">{{ $conteos[$val] ?? 0 }}</span>
                    @else
                        <span class="ml-1 text-xs opacity-70">{{ array_sum($conteos) }}</span>
                    @endif
                </a>
            @endforeach
        </div>
        <a href="{{ route('admin.suscripciones.create') }}" class="btn-primary text-sm">+ Nueva</a>
    </div>

    <div class="bg-card border border-base rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-muted uppercase border-b border-base">
                    <th class="text-left px-5 py-3">Agencia</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Plan</th>
                    <th class="text-left px-4 py-3 hidden lg:table-cell">Inicio</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Vencimiento</th>
                    <th class="text-center px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base">
                @forelse($suscripciones as $sus)
                    @php
                        $statusColors = [
                            'activa'   => 'bg-green-500/10 text-green-400 border-green-500/20',
                            'prueba'   => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                            'vencida'  => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                            'cancelada'=> 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                        ];
                        $sc = $statusColors[$sus->status] ?? 'bg-card2 text-muted border-base';
                    @endphp
                    <tr class="hover:bg-card2 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.agencias.show', $sus->agencia) }}"
                               class="font-medium text-base hover:text-brand-orange transition-colors">
                                {{ $sus->agencia?->nombre ?? '—' }}
                            </a>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-muted">{{ $sus->plan?->nombre ?? '—' }}</td>
                        <td class="px-4 py-3 hidden lg:table-cell text-muted">{{ $sus->fecha_inicio->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 hidden md:table-cell text-muted">
                            {{ $sus->fecha_vencimiento->format('d/m/Y') }}
                            @if($sus->status === 'activa' && $sus->fecha_vencimiento->diffInDays(now()) <= 7 && $sus->fecha_vencimiento->isFuture())
                                <span class="ml-1 text-yellow-400 text-xs">⚠ pronto</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full border {{ $sc }} capitalize">
                                {{ $sus->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @if($sus->status === 'activa')
                                    <form method="POST" action="{{ route('admin.suscripciones.cancelar', $sus) }}"
                                          onsubmit="return confirm('¿Cancelar esta suscripción?')">
                                        @csrf @method('PATCH')
                                        <button class="text-xs text-red-400 hover:underline">Cancelar</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.suscripciones.edit', $sus) }}"
                                   class="text-xs text-brand-orange hover:underline">Editar</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-muted text-sm">Sin suscripciones en esta categoría.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suscripciones->hasPages())
        <div class="mt-5">{{ $suscripciones->links('components.paginacion') }}</div>
    @endif

</x-admin-layout>
