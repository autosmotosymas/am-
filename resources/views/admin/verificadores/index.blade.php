<x-admin-layout title="Verificadores">

    <div class="flex justify-end mb-5">
        <a href="{{ route('admin.verificadores.create') }}" class="btn-primary">+ Nuevo verificador</a>
    </div>

    <div class="bg-card border border-base rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-muted uppercase border-b border-base">
                    <th class="text-left px-5 py-3">Verificador</th>
                    <th class="text-left px-4 py-3 hidden md:table-cell">Zona</th>
                    <th class="text-center px-4 py-3 hidden md:table-cell">Certificaciones</th>
                    <th class="text-center px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base">
                @forelse($verificadores as $v)
                    <tr class="hover:bg-card2 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-base">{{ $v->nombre }}</p>
                            <p class="text-xs text-muted">{{ $v->email }}</p>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-muted">{{ $v->zona ?? '—' }}</td>
                        <td class="px-4 py-3 text-center hidden md:table-cell text-muted">{{ $v->certificaciones_count }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full border
                                {{ $v->activo ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">
                                {{ $v->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.verificadores.edit', $v) }}" class="text-xs text-brand-orange hover:underline">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-12 text-muted text-base">Sin verificadores.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($verificadores->hasPages())
        <div class="mt-5">{{ $verificadores->links('components.paginacion') }}</div>
    @endif

</x-admin-layout>
