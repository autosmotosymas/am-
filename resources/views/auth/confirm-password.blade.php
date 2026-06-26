<x-guest-layout>

    <h2 class="text-2xl font-bold text-white mb-1">Confirma tu contraseña</h2>
    <p class="text-base text-gray-400 mb-8">
        Esta es un área segura. Por favor confirma tu contraseña antes de continuar.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <label for="password" class="label-amm-dark">Contraseña</label>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password"
                   class="input-amm-dark @error('password') border-red-500/60 @enderror"
                   placeholder="••••••••">
            @error('password')
                <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full">
            Confirmar
        </button>
    </form>

</x-guest-layout>
