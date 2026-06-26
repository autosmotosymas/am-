<x-admin-layout title="Vendedores">

    <div class="flex justify-end mb-5">
        <a href="{{ route('admin.vendedores.create') }}" class="btn-primary">+ Nuevo vendedor</a>
    </div>

    <div class="bg-card border border-base rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-muted uppercase border-b border-base">
                    <th class="text-left px-5 py-3">Vendedor</th>
                    <th class="text-center px-4 py-3 hidden md:table-cell">Agencias</th>
                    <th class="text-center px-4 py-3 hidden lg:table-cell">Teléfono</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base">
                @forelse($vendedores as $v)
                    <tr class="hover:bg-card2 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-base">{{ $v->name }}</p>
                            <p class="text-xs text-muted">{{ $v->email }}</p>
                        </td>
                        <td class="px-4 py-3 text-center hidden md:table-cell text-muted">
                            {{ $v->agencias_vendidas_count }}
                        </td>
                        <td class="px-4 py-3 text-center hidden lg:table-cell text-muted">
                            {{ $v->telefono ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('admin.vendedores.show', $v) }}"
                                   class="text-xs text-brand-orange hover:underline px-2">Ver</a>
                                <form method="POST" action="{{ route('admin.vendedores.destroy', $v) }}"
                                      onsubmit="return confirm('¿Eliminar a {{ addslashes($v->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-400 hover:text-red-300 px-2">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-muted text-base">Sin vendedores registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($vendedores->hasPages())
        <div class="mt-5">{{ $vendedores->links('components.paginacion') }}</div>
    @endif

</x-admin-layout>
