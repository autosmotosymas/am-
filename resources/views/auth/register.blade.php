<x-guest-layout>

    <h2 class="text-2xl font-bold text-white mb-1">Crea tu cuenta</h2>
    <p class="text-sm text-gray-400 mb-8">Busca y sigue autos de tu interés</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Nombre --}}
        <div>
            <label for="name" class="block text-xs font-semibold text-gray-300 mb-1.5">Nombre completo</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors @error('name') border-red-500/60 @enderror"
                   placeholder="Tu nombre">
            @error('name')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Teléfono --}}
        <div>
            <label for="telefono" class="block text-xs font-semibold text-gray-300 mb-1.5">
                Teléfono <span class="text-gray-600 font-normal">(opcional)</span>
            </label>
            <input id="telefono" type="tel" name="telefono" value="{{ old('telefono') }}"
                   autocomplete="tel"
                   class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors"
                   placeholder="33 1234 5678">
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-300 mb-1.5">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username"
                   class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors @error('email') border-red-500/60 @enderror"
                   placeholder="tu@correo.com">
            @error('email')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-semibold text-gray-300 mb-1.5">Contraseña</label>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password"
                   class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors @error('password') border-red-500/60 @enderror"
                   placeholder="Mínimo 8 caracteres">
            @error('password')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmar password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-gray-300 mb-1.5">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password"
                   class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange transition-colors"
                   placeholder="Repite la contraseña">
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3 text-base mt-2">
            Crear cuenta
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="text-brand-orange hover:underline font-medium">
            Ingresar
        </a>
    </p>

    <p class="mt-4 text-center text-xs text-gray-600">
        Al registrarte aceptas nuestros
        <a href="#" class="hover:text-gray-400 underline">términos y condiciones</a>
    </p>

</x-guest-layout>
