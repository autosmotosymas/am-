<x-vendedor-layout title="Mi perfil">

<div class="px-4 pt-5 pb-6 space-y-4">

    {{-- Datos personales --}}
    <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
        <h2 class="text-sm font-semibold text-white mb-4">Datos personales</h2>

        <form method="POST" action="{{ route('vendedor.perfil.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Nombre</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors
                              @error('name') border-red-500/60 @enderror">
                @error('name')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors
                              @error('email') border-red-500/60 @enderror">
                @error('email')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Teléfono</label>
                <input type="tel" name="telefono" value="{{ old('telefono', $user->telefono) }}"
                       class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors"
                       placeholder="33 1234 5678">
            </div>

            <button type="submit" class="btn-primary w-full justify-center">
                Guardar datos
            </button>
        </form>
    </div>

    {{-- Cambiar contraseña --}}
    <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
        <h2 class="text-sm font-semibold text-white mb-4">Cambiar contraseña</h2>

        <form method="POST" action="{{ route('vendedor.perfil.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Nueva contraseña</label>
                <input type="password" name="password"
                       class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors
                              @error('password') border-red-500/60 @enderror"
                       placeholder="Mínimo 8 caracteres">
                @error('password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Confirmar contraseña</label>
                <input type="password" name="password_confirmation"
                       class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors"
                       placeholder="Repetir contraseña">
            </div>

            <button type="submit" class="btn-primary w-full justify-center">
                Actualizar contraseña
            </button>
        </form>
    </div>

    {{-- Cerrar sesión --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="w-full py-3 rounded-xl border border-red-500/20 text-red-400 text-sm font-medium hover:bg-red-500/10 transition-colors">
            Cerrar sesión
        </button>
    </form>

</div>

</x-vendedor-layout>
