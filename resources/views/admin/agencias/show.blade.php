<x-admin-layout :title="$agencia->nombre">

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">

        {{-- Info principal --}}
        <div class="space-y-5">

            {{-- Datos --}}
            <div class="bg-card border border-base rounded-2xl p-6">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-base">
                            {{ $agencia->nombre }}
                            @if($agencia->verificada)
                                <span class="text-brand-orange">✓</span>
                            @endif
                        </h2>
                        <p class="text-sm text-muted">{{ $agencia->ciudad }}, {{ $agencia->estado }}</p>
                    </div>
                    <span class="shrink-0 text-sm px-3 py-1 rounded-full border
                        {{ $agencia->activo ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                        {{ $agencia->activo ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach(['Email' => $agencia->email, 'Teléfono' => $agencia->telefono, 'WhatsApp' => $agencia->whatsapp, 'Dirección' => $agencia->direccion] as $label => $valor)
                        @if($valor)
                            <div class="bg-card2 rounded-xl p-3">
                                <p class="text-xs text-muted mb-0.5">{{ $label }}</p>
                                <p class="text-base font-medium">{{ $valor }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Acciones --}}
                <div class="flex flex-wrap gap-2 mt-5 pt-5 border-t border-base">
                    @if(!$agencia->verificada)
                        <form method="POST" action="{{ route('admin.agencias.verificar', $agencia) }}">
                            @csrf @method('PATCH')
                            <button class="btn-primary text-sm">✓ Verificar agencia</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.agencias.toggle', $agencia) }}">
                        @csrf @method('PATCH')
                        <button class="btn-outline text-sm {{ $agencia->activo ? 'text-red-400 border-red-500/20' : '' }}">
                            {{ $agencia->activo ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                    <a href="{{ route('agencia.show', $agencia) }}" target="_blank" class="btn-ghost text-sm">
                        Ver perfil público →
                    </a>
                </div>
            </div>

            {{-- Últimos vehículos --}}
            <div class="bg-card border border-base rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-base">
                    <h3 class="font-semibold text-base">Vehículos recientes</h3>
                </div>
                @if($agencia->vehiculos->isEmpty())
                    <p class="text-sm text-muted text-center py-8">Sin vehículos.</p>
                @else
                    <div class="divide-y divide-base">
                        @foreach($agencia->vehiculos as $v)
                            <div class="flex items-center gap-3 px-5 py-3">
                                <div class="w-10 h-10 rounded-lg bg-card2 overflow-hidden shrink-0">
                                    @if($v->fotoPrincipal)
                                        <img src="{{ $v->fotoPrincipal->url }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-base truncate">{{ $v->anio }} {{ $v->marca }} {{ $v->modelo }}</p>
                                    <p class="text-xs text-muted">{{ $v->precio_formateado }}</p>
                                </div>
                                <span class="text-xs capitalize text-muted">{{ $v->status }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            {{-- Suscripción activa --}}
            <div class="bg-card border border-base rounded-2xl p-5">
                <h3 class="font-semibold text-base mb-3">Suscripción</h3>
                @if($agencia->suscripcionActiva)
                    @php $sus = $agencia->suscripcionActiva; @endphp
                    <p class="text-lg font-black text-green-400">{{ $sus->plan?->nombre }}</p>
                    <p class="text-xs text-muted mt-1">Vence: {{ $sus->fecha_vencimiento->format('d/m/Y') }}</p>
                @else
                    <p class="text-sm text-red-400">Sin suscripción activa</p>
                @endif

                {{-- Activar manual --}}
                <form method="POST" action="{{ route('admin.agencias.suscribir', $agencia) }}"
                      class="mt-4 pt-4 border-t border-base space-y-3">
                    @csrf
                    <select name="plan_id"
                            class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-brand-orange">
                        @foreach($planes as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->nombre }} — {{ $plan->precio_formateado }}/mes</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <input type="number" name="meses" value="1" min="1" max="24"
                               class="w-20 bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-brand-orange">
                        <span class="text-sm text-muted self-center">mes(es)</span>
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center text-sm">
                        Activar suscripción
                    </button>
                </form>
            </div>

            {{-- Historial suscripciones --}}
            @if($agencia->suscripciones->isNotEmpty())
                <div class="bg-card border border-base rounded-2xl overflow-hidden">
                    <div class="px-5 py-3 border-b border-base">
                        <h3 class="font-semibold text-base text-sm">Historial</h3>
                    </div>
                    <div class="divide-y divide-base">
                        @foreach($agencia->suscripciones->take(5) as $sus)
                            <div class="px-5 py-3">
                                <div class="flex justify-between items-center">
                                    <p class="text-xs font-medium text-base">{{ $sus->plan?->nombre }}</p>
                                    <span class="text-[10px] capitalize px-1.5 py-0.5 rounded-full
                                        {{ $sus->status === 'activa' ? 'bg-green-500/10 text-green-400' : 'bg-gray-500/10 text-gray-400' }}">
                                        {{ $sus->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-muted mt-0.5">
                                    {{ $sus->fecha_inicio->format('d/m/Y') }} — {{ $sus->fecha_vencimiento->format('d/m/Y') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-admin-layout>
