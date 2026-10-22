<x-admin-layout title="Editar usuario">

<div class="max-w-xl">

    <a href="{{ route('admin.usuarios.index') }}"
       class="inline-flex items-center gap-1 text-sm text-muted hover:text-base mb-5 transition-colors">
        ← Volver a usuarios
    </a>

    @if(session('ok'))
        <div class="mb-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl px-5 py-3 text-sm">
            {!! session('ok') !!}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-5 py-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}"
          class="bg-card border border-base rounded-2xl p-6 space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Nombre completo *</label>
            <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required
                   class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Email *</label>
            <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required
                   class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Teléfono</label>
            <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                   class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Rol *</label>
            <select name="rol" required
                    class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                @foreach($roles as $r)
                    <option value="{{ $r }}" @selected(old('rol', $usuario->roles->first()?->name) === $r)>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Agencia asociada</label>
            <select name="agencia_id"
                    class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                <option value="">— Ninguna —</option>
                @foreach($agencias as $ag)
                    <option value="{{ $ag->id }}" @selected(old('agencia_id', $usuario->agencia_id) == $ag->id)>{{ $ag->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="pt-2 flex gap-3">
            <button type="submit" class="btn-primary flex-1">Guardar cambios</button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn-outline px-6">Cancelar</a>
        </div>
    </form>

    {{-- Acciones peligrosas --}}
    <div class="mt-6 bg-card border border-base rounded-2xl p-6 space-y-4">
        <h3 class="text-sm font-semibold text-base">Acciones de seguridad</h3>

        <div class="flex flex-wrap items-center justify-between gap-3 py-3 border-b border-base">
            <div>
                <p class="text-sm font-medium text-base">Resetear contraseña</p>
                <p class="text-xs text-muted">Se generará una contraseña aleatoria que se mostrará una sola vez.</p>
            </div>
            <form method="POST" action="{{ route('admin.usuarios.reset-password', $usuario) }}">
                @csrf
                <button type="submit"
                        onclick="return confirm('¿Generar nueva contraseña para {{ addslashes($usuario->name) }}? La actual dejará de funcionar.')"
                        class="btn-outline text-sm px-4">
                    Generar nueva contraseña
                </button>
            </form>
        </div>

        @if($usuario->id !== auth()->id())
            <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                <div>
                    <p class="text-sm font-medium {{ $usuario->activo ? 'text-red-400' : 'text-green-400' }}">
                        {{ $usuario->activo ? 'Desactivar usuario' : 'Activar usuario' }}
                    </p>
                    <p class="text-xs text-muted">
                        {{ $usuario->activo
                            ? 'El usuario no podrá iniciar sesión mientras esté desactivado.'
                            : 'El usuario podrá volver a iniciar sesión.' }}
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.usuarios.toggle', $usuario) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="text-sm px-4 py-2 rounded-xl border transition-colors
                                {{ $usuario->activo
                                    ? 'border-red-500/30 text-red-400 hover:bg-red-500/10'
                                    : 'border-green-500/30 text-green-400 hover:bg-green-500/10' }}">
                        {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                    </button>
                </form>
            </div>
        @endif
    </div>

</div>

</x-admin-layout>
