<x-guest-layout>

    <h2 class="text-2xl font-bold text-white mb-1">Bienvenido de regreso</h2>
    <p class="text-base text-gray-400 mb-8">Ingresa a tu cuenta para continuar</p>

    @if(session('status'))
        <div class="mb-4 text-base text-green-400 bg-green-500/10 border border-green-500/20 rounded-xl px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="label-amm-dark">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="input-amm-dark @error('email') border-red-500/60 @enderror"
                   placeholder="tu@correo.com">
            @error('email')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="label-amm-dark mb-0">Contraseña</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-brand-orange hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password"
                   class="input-amm-dark @error('password') border-red-500/60 @enderror"
                   placeholder="••••••••">
            @error('password')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Recuérdame --}}
        <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded accent-brand-orange">
            <span class="text-base text-gray-400">Mantener sesión iniciada</span>
        </label>

        <button type="submit" class="btn-primary w-full mt-2">
            Ingresar
        </button>
    </form>

    <p class="mt-6 text-center text-base text-gray-500">
        ¿No tienes cuenta?
        <a href="{{ route('register') }}" class="text-brand-orange hover:underline font-medium">
            Regístrate gratis
        </a>
    </p>

</x-guest-layout>
