<x-guest-layout>

    <h2 class="text-2xl font-bold text-white mb-1">¿Olvidaste tu contraseña?</h2>
    <p class="text-base text-gray-400 mb-8">
        Ingresa tu correo y te enviaremos un enlace para restablecerla.
    </p>

    @if(session('status'))
        <div class="mb-6 text-base text-green-400 bg-green-500/10 border border-green-500/20 rounded-xl px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

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

        <button type="submit" class="btn-primary w-full">
            Enviar enlace de recuperación
        </button>
    </form>

    <p class="mt-6 text-center text-base text-gray-500">
        <a href="{{ route('login') }}" class="text-brand-orange hover:underline font-medium">
            Volver al inicio de sesión
        </a>
    </p>

</x-guest-layout>
