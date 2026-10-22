<x-admin-layout title="Usuarios">

<div x-data="{ selected: [], accion: 'activar' }">

    <div class="flex flex-col sm:flex-row gap-3 justify-between mb-5">
        <form method="GET" class="flex flex-wrap gap-2 flex-1">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Nombre o email…"
                   class="flex-1 min-w-[160px] bg-card border border-base text-base rounded-xl px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors">

            <select name="rol"
                    class="bg-card border border-base text-base rounded-xl px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors text-sm">
                <option value="">Todos los roles</option>
                @foreach($roles as $r)
                    <option value="{{ $r }}" @selected(request('rol') === $r)>{{ ucfirst($r) }}</option>
                @endforeach
            </select>

            <label class="flex items-center gap-2 text-sm text-muted cursor-pointer select-none">
                <input type="checkbox" name="solo_inactivos" value="1"
                       @checked(request('solo_inactivos'))
                       class="rounded border-base">
                Solo inactivos
            </label>

            <button type="submit" class="btn-outline px-4">Filtrar</button>
        </form>
        <a href="{{ route('admin.usuarios.create') }}" class="btn-primary whitespace-nowrap">+ Nuevo usuario</a>
    </div>

    {{-- Flash --}}
    @if(session('ok'))
        <div class="mb-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl px-5 py-3 text-sm">
            {!! session('ok') !!}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-5 py-3 text-sm">
            {!! session('error') !!}
        </div>
    @endif

    <div class="bg-card border border-base rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs text-muted uppercase border-b border-base">
                    <th class="text-left px-5 py-3">Usuario</th>
                    <th class="text-center px-4 py-3 hidden md:table-cell">Rol</th>
                    <th class="text-center px-4 py-3 hidden lg:table-cell">Agencia</th>
                    <th class="text-center px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base">
                @forelse($usuarios as $u)
                    <tr class="hover:bg-card2 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-base">{{ $u->name }}</p>
                            <p class="text-xs text-muted">{{ $u->email }}</p>
                            @if($u->telefono)
                                <p class="text-xs text-muted">{{ $u->telefono }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center hidden md:table-cell">
                            @foreach($u->roles as $rol)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-brand-orange/10 text-brand-orange border border-brand-orange/20">
                                    {{ $rol->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 text-center hidden lg:table-cell text-muted text-xs">
                            {{ $u->agencia?->nombre ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full border
                                {{ $u->activo ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                                {{ $u->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 justify-end flex-wrap">
                                <a href="{{ route('admin.usuarios.edit', $u) }}"
                                   class="text-xs text-brand-orange hover:underline px-2">Editar</a>

                                <form method="POST" action="{{ route('admin.usuarios.reset-password', $u) }}">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('¿Generar nueva contraseña para {{ addslashes($u->name) }}?')"
                                            class="text-xs text-muted hover:text-base px-2">
                                        Reset pwd
                                    </button>
                                </form>

                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.usuarios.toggle-activo', $u) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="text-xs px-2 {{ $u->activo ? 'text-red-400 hover:text-red-300' : 'text-green-400 hover:text-green-300' }}">
                                            {{ $u->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-muted text-base">Sin usuarios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($usuarios->hasPages())
        <div class="mt-5">{{ $usuarios->links('components.paginacion') }}</div>
    @endif

</div>

</x-admin-layout>
