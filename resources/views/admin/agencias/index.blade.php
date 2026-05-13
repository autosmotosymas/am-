<x-admin-layout title="Agencias">

    <div class="flex flex-col sm:flex-row gap-3 justify-between mb-5">
        <form method="GET" class="flex gap-2 flex-1 max-w-sm">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Buscar agencia…"
                   class="flex-1 bg-card border border-base text-base text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors">
            <button type="submit" class="btn-outline px-4">Buscar</button>
        </form>
        <a href="{{ route('admin.agencias.create') }}" class="btn-primary">+ Nueva agencia</a>
    </div>

    <div class="bg-card border border-base rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-muted uppercase border-b border-base">
                    <th class="text-left px-5 py-3">Agencia</th>
                    <th class="text-center px-4 py-3 hidden md:table-cell">Vehículos</th>
                    <th class="text-center px-4 py-3 hidden lg:table-cell">Plan</th>
                    <th class="text-center px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base">
                @forelse($agencias as $ag)
                    <tr class="hover:bg-card2 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div>
                                    <p class="font-medium text-base">
                                        {{ $ag->nombre }}
                                        @if($ag->verificada)
                                            <span class="text-brand-orange text-xs">✓</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-muted">{{ $ag->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center hidden md:table-cell text-muted">
                            {{ $ag->vehiculos_count }}
                        </td>
                        <td class="px-4 py-3 text-center hidden lg:table-cell">
                            <span class="text-xs {{ $ag->suscripcionActiva ? 'text-green-400' : 'text-muted' }}">
                                {{ $ag->suscripcionActiva?->plan?->nombre ?? 'Sin plan' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full border
                                {{ $ag->activo ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                                {{ $ag->activo ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 justify-end">
                                <a href="{{ route('admin.agencias.show', $ag) }}"
                                   class="text-xs text-brand-orange hover:underline px-2">Ver</a>
                                <form method="POST" action="{{ route('admin.agencias.toggle', $ag) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs text-muted hover:text-base px-2">
                                        {{ $ag->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-muted text-sm">Sin agencias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($agencias->hasPages())
        <div class="mt-5">{{ $agencias->links('components.paginacion') }}</div>
    @endif

</x-admin-layout>
