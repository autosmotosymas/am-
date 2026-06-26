<x-vendedor-layout :title="$agencia->nombre" back="{{ route('vendedor.dashboard') }}">

    <x-slot name="headerAction">
        <a href="{{ route('vendedor.vehiculos.create', $agencia) }}"
           class="w-8 h-8 flex items-center justify-center rounded-full bg-brand-orange hover:bg-brand-orange2 transition-colors">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
        </a>
    </x-slot>

    <div class="px-4 pt-5">

        <div class="mb-5">
            <p class="text-xs text-gray-500">{{ $agencia->ciudad }}, {{ $agencia->estado }}</p>
            <p class="text-xs text-gray-600 mt-0.5">
                {{ $vehiculos->total() }} {{ $vehiculos->total() === 1 ? 'vehículo capturado' : 'vehículos capturados' }}
                @if(!$agencia->activo)
                    · <span class="text-yellow-400">Pendiente de activación</span>
                @endif
            </p>
        </div>

        @if($vehiculos->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                </div>
                <p class="text-white font-semibold mb-1">Sin vehículos aún</p>
                <p class="text-sm text-gray-500 mb-6 max-w-xs">
                    Captura el inventario de {{ $agencia->nombre }}.
                </p>
                <a href="{{ route('vendedor.vehiculos.create', $agencia) }}" class="btn-primary">
                    Capturar vehículo
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($vehiculos as $v)
                    <div class="flex items-center gap-3 bg-white/5 rounded-2xl p-3 border border-white/8">

                        {{-- Foto --}}
                        <div class="w-16 h-14 rounded-xl bg-white/10 overflow-hidden shrink-0">
                            @if($v->fotoPrincipal)
                                <img src="{{ $v->fotoPrincipal->url }}"
                                     alt="{{ $v->marca }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">
                                {{ $v->anio }} {{ $v->marca }} {{ $v->modelo }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $v->version ?? $v->color }} · {{ $v->kilometraje_formateado }}
                            </p>
                            <p class="text-sm font-bold text-brand-orange mt-0.5">
                                {{ $v->precio_formateado }}
                            </p>
                        </div>

                        {{-- Status --}}
                        <div class="shrink-0 text-right">
                            @php
                                $st = match($v->status) {
                                    'disponible' => ['Activo',   'text-green-400'],
                                    'inactivo'   => ['Borrador', 'text-yellow-400'],
                                    'vendido'    => ['Vendido',  'text-gray-500'],
                                    default      => [$v->status, 'text-gray-400'],
                                };
                            @endphp
                            <span class="text-xs {{ $st[1] }} font-medium">{{ $st[0] }}</span>
                            <p class="text-[10px] text-gray-600 mt-0.5">{{ $v->created_at->diffForHumans(null, true) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($vehiculos->hasPages())
                <div class="flex justify-between mt-6 text-sm">
                    @if($vehiculos->onFirstPage())
                        <span class="text-gray-600">← Anterior</span>
                    @else
                        <a href="{{ $vehiculos->previousPageUrl() }}" class="text-brand-orange">← Anterior</a>
                    @endif
                    <span class="text-gray-500">{{ $vehiculos->currentPage() }} / {{ $vehiculos->lastPage() }}</span>
                    @if($vehiculos->hasMorePages())
                        <a href="{{ $vehiculos->nextPageUrl() }}" class="text-brand-orange">Siguiente →</a>
                    @else
                        <span class="text-gray-600">Siguiente →</span>
                    @endif
                </div>
            @endif

            <div class="mt-4 pb-2">
                <a href="{{ route('vendedor.vehiculos.create', $agencia) }}"
                   class="flex items-center justify-center gap-2 w-full border border-white/10 rounded-2xl py-3 text-sm text-gray-400 hover:text-white hover:border-white/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Capturar otro vehículo
                </a>
            </div>
        @endif
    </div>

</x-vendedor-layout>
