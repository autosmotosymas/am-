<x-guest-layout>

    <h2 class="text-2xl font-bold text-white mb-1">Verifica tu correo</h2>
    <p class="text-base text-gray-400 mb-6">
        Te enviamos un enlace de verificación. Revisa tu bandeja de entrada y haz clic en él para activar tu cuenta.
    </p>

    @if(session('status') == 'verification-link-sent')
        <div class="mb-6 text-base text-green-400 bg-green-500/10 border border-green-500/20 rounded-xl px-4 py-3">
            Se envió un nuevo enlace de verificación a tu correo.
        </div>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full">
                Reenviar correo de verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-outline w-full" style="border-color:rgba(255,255,255,0.15); color:#aaa;">
                Cerrar sesión
            </button>
        </form>
    </div>

</x-guest-layout>
