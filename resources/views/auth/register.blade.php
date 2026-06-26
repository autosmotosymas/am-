<x-guest-layout>

    <h2 class="text-2xl font-bold text-white mb-1">Crea tu cuenta</h2>
    <p class="text-base text-gray-400 mb-8">Busca y sigue autos de tu interés</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Nombre --}}
        <div>
            <label for="name" class="label-amm-dark">Nombre completo</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="input-amm-dark @error('name') border-red-500/60 @enderror"
                   placeholder="Tu nombre">
            @error('name')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Teléfono --}}
        <div>
            <label for="telefono" class="label-amm-dark">
                Teléfono <span class="text-gray-600 font-normal">(opcional)</span>
            </label>
            <input id="telefono" type="tel" name="telefono" value="{{ old('telefono') }}"
                   autocomplete="tel"
                   class="input-amm-dark"
                   placeholder="33 1234 5678">
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="label-amm-dark">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="username"
                   class="input-amm-dark @error('email') border-red-500/60 @enderror"
                   placeholder="tu@correo.com">
            @error('email')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="label-amm-dark">Contraseña</label>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password"
                   class="input-amm-dark @error('password') border-red-500/60 @enderror"
                   placeholder="Mínimo 8 caracteres">
            @error('password')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmar password --}}
        <div>
            <label for="password_confirmation" class="label-amm-dark">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password"
                   class="input-amm-dark"
                   placeholder="Repite la contraseña">
        </div>

        <button type="submit" class="btn-primary w-full mt-2">
            Crear cuenta
        </button>
    </form>

    <p class="mt-6 text-center text-base text-gray-500">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="text-brand-orange hover:underline font-medium">
            Ingresar
        </a>
    </p>

    <p class="mt-4 text-center text-sm text-gray-600">
        Al registrarte aceptas nuestros
        <a href="#" class="hover:text-gray-400 underline">términos y condiciones</a>
    </p>

</x-guest-layout>
