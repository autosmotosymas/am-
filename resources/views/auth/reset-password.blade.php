<x-guest-layout>

    <h2 class="text-2xl font-bold text-white mb-1">Nueva contraseña</h2>
    <p class="text-base text-gray-400 mb-8">Elige una contraseña segura para tu cuenta.</p>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="label-amm-dark">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                   required autofocus autocomplete="username"
                   class="input-amm-dark @error('email') border-red-500/60 @enderror"
                   placeholder="tu@correo.com">
            @error('email')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="label-amm-dark">Nueva contraseña</label>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password"
                   class="input-amm-dark @error('password') border-red-500/60 @enderror"
                   placeholder="Mínimo 8 caracteres">
            @error('password')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="label-amm-dark">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   required autocomplete="new-password"
                   class="input-amm-dark"
                   placeholder="Repite la contraseña">
        </div>

        <button type="submit" class="btn-primary w-full">
            Restablecer contraseña
        </button>
    </form>

</x-guest-layout>
