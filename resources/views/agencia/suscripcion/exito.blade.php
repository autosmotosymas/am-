<x-agencia-layout title="¡Suscripción activada!">

    <div class="max-w-md mx-auto text-center py-12">
        <div class="w-20 h-20 rounded-full bg-green-500/15 border border-green-500/20 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-base mb-2">¡Pago exitoso!</h1>
        <p class="text-base text-muted mb-8">
            Tu suscripción ya está activa. Puedes empezar a publicar tu inventario ahora mismo.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('agencia.vehiculos.create') }}" class="btn-primary">
                + Agregar vehículo
            </a>
            <a href="{{ route('agencia.dashboard') }}" class="btn-outline">
                Ir al dashboard
            </a>
        </div>
    </div>

</x-agencia-layout>
