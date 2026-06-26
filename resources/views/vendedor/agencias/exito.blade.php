<x-vendedor-layout title="¡Pago exitoso!" back="{{ route('vendedor.dashboard') }}">

<div class="flex flex-col items-center justify-center px-4 py-20 text-center">
    <div class="w-20 h-20 rounded-full bg-green-500/15 flex items-center justify-center mb-6">
        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
    </div>

    <h1 class="text-xl font-bold text-white mb-2">¡Pago completado!</h1>
    <p class="text-sm text-gray-400 max-w-xs mb-8">
        La suscripción quedó activa. La agencia ya aparece en el sitio y sus vehículos son visibles para los compradores.
    </p>

    <a href="{{ route('vendedor.dashboard') }}" class="btn-primary w-full max-w-xs">
        Ver mis agencias
    </a>
</div>

</x-vendedor-layout>
