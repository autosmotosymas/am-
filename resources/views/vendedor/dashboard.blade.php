<x-vendedor-layout title="Mis agencias">

<div class="px-4 pt-5">

    <div class="mb-5">
        <p class="text-sm text-gray-400">
            Hola, <span class="text-white font-medium">{{ Str::words(auth()->user()->name, 1, '') }}</span>
        </p>
        <p class="text-xs text-gray-600 mt-0.5">
            {{ $agencias->count() }} {{ $agencias->count() === 1 ? 'agencia registrada' : 'agencias registradas' }} ·
            {{ $totalVehiculos }} {{ $totalVehiculos === 1 ? 'vehículo' : 'vehículos' }}
        </p>
    </div>

    @if($agencias->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72M6.75 18h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .414.336.75.75.75z"/>
                </svg>
            </div>
            <p class="text-white font-semibold mb-1">Sin agencias aún</p>
            <p class="text-sm text-gray-500 mb-6 max-w-xs">
                Toca el botón <span class="text-brand-orange">+</span> para registrar la primera agencia.
            </p>
            <a href="{{ route('vendedor.agencias.create') }}" class="btn-primary">
                Registrar agencia
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($agencias as $ag)
                <a href="{{ route('vendedor.agencias.show', $ag) }}"
                   class="flex items-center gap-3 bg-white/5 rounded-2xl p-3 border border-white/8 active:scale-95 transition-all block">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ $ag->nombre }}</p>
                        <p class="text-xs text-gray-500">{{ $ag->ciudad }}, {{ $ag->estado }}</p>
                        <p class="text-xs text-brand-orange mt-0.5">{{ $ag->vehiculos_count }} {{ $ag->vehiculos_count === 1 ? 'vehículo' : 'vehículos' }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="text-xs {{ $ag->activo ? 'text-green-400' : 'text-yellow-400' }} font-medium">
                            {{ $ag->activo ? 'Activa' : 'Pendiente' }}
                        </span>
                        <svg class="w-4 h-4 text-gray-600 mt-1 ml-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6 pb-2">
            <a href="{{ route('vendedor.agencias.create') }}"
               class="flex items-center justify-center gap-2 w-full border border-white/10 rounded-2xl py-3 text-sm text-gray-400 hover:text-white hover:border-white/20 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Registrar otra agencia
            </a>
        </div>
    @endif
</div>

</x-vendedor-layout>
