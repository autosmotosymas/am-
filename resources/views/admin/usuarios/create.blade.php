<x-admin-layout title="Nuevo usuario">

<div class="max-w-xl">

    <a href="{{ route('admin.usuarios.index') }}"
       class="inline-flex items-center gap-1 text-sm text-muted hover:text-base mb-5 transition-colors">
        ← Volver a usuarios
    </a>

    @if($errors->any())
        <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-5 py-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.usuarios.store') }}"
          class="bg-card border border-base rounded-2xl p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Nombre completo *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Teléfono</label>
            <input type="text" name="telefono" value="{{ old('telefono') }}"
                   placeholder="10 dígitos"
                   class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Contraseña</label>
            <input type="text" name="password" value="{{ old('password') }}"
                   placeholder="Dejar vacío para generar automáticamente"
                   class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors font-mono">
            <p class="text-xs text-muted mt-1">Si la dejas vacía, se generará una contraseña aleatoria y se mostrará una sola vez.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Rol *</label>
            <select name="rol" required
                    class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                <option value="">— Selecciona un rol —</option>
                @foreach($roles as $r)
                    <option value="{{ $r }}" @selected(old('rol') === $r)>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-base mb-1.5">Agencia asociada</label>
            <select name="agencia_id"
                    class="w-full bg-card2 border border-base text-base rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                <option value="">— Ninguna (admin / capturador / comprador) —</option>
                @foreach($agencias as $ag)
                    <option value="{{ $ag->id }}" @selected(old('agencia_id') == $ag->id)>{{ $ag->nombre }}</option>
                @endforeach
            </select>
            <p class="text-xs text-muted mt-1">Requerido para roles <code>agencia</code> y <code>vendedor</code>.</p>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full">Crear usuario</button>
        </div>
    </form>
</div>

</x-admin-layout>
