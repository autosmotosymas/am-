<x-agencia-layout title="Dashboard">

    {{-- Stats principales --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label' => 'Activos',    'valor' => $stats['activos'],    'color' => 'text-green-400',       'bg' => 'bg-green-500/10'],
            ['label' => 'Leads nuevos','valor' => $stats['leads_new'], 'color' => 'text-brand-orange',    'bg' => 'bg-brand-orange/10'],
            ['label' => 'Vistas totales','valor'=> number_format($stats['vistas']), 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/10'],
            ['label' => 'Vendidos',   'valor' => $stats['vendidos'],   'color' => 'text-muted',           'bg' => 'bg-card2'],
        ] as $s)
            <div class="bg-card border border-base rounded-2xl p-5">
                <p class="text-xs text-muted mb-2">{{ $s['label'] }}</p>
                <p class="text-3xl font-black {{ $s['color'] }}">{{ $s['valor'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-6">

        {{-- Leads recientes --}}
        <div class="bg-card border border-base rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-base">
                <h2 class="font-semibold text-base">Leads recientes</h2>
                <a href="{{ route('agencia.leads.index') }}" class="text-xs text-brand-orange hover:underline">Ver todos</a>
            </div>

            @if($leadsRecientes->isEmpty())
                <p class="text-base text-muted text-center py-10">Aún no hay leads.</p>
            @else
                <div class="divide-y divide-base">
                    @foreach($leadsRecientes as $lead)
                        <div class="flex items-center gap-4 px-5 py-3.5">
                            <div class="w-9 h-9 rounded-xl bg-brand-orange/10 flex items-center justify-center text-brand-orange font-bold text-sm shrink-0">
                                {{ strtoupper(substr($lead->nombre, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-base truncate">{{ $lead->nombre }}</p>
                                <p class="text-xs text-muted truncate">
                                    {{ $lead->vehiculo?->marca }} {{ $lead->vehiculo?->modelo }}
                                    · {{ $lead->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="shrink-0 text-xs px-2 py-0.5 rounded-full
                                @if($lead->status === 'nuevo') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                @elseif($lead->status === 'en_proceso') bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                                @else bg-gray-500/10 text-gray-400 border border-gray-500/20 @endif">
                                {{ ['nuevo'=>'Nuevo','en_proceso'=>'En proceso','cerrado_ganado'=>'Ganado','cerrado_perdido'=>'Cerrado'][$lead->status] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Panel derecho --}}
        <div class="space-y-4">

            {{-- Suscripción --}}
            <div class="bg-card border border-base rounded-2xl p-5">
                <h3 class="font-semibold text-base mb-3">Suscripción</h3>
                @if($agencia->suscripcionActiva)
                    @php $sus = $agencia->suscripcionActiva; $plan = $sus->plan; @endphp
                    <p class="text-lg font-black text-brand-orange mb-1">{{ $plan?->nombre }}</p>
                    <p class="text-xs text-muted">Vence: {{ $sus->fecha_vencimiento->format('d/m/Y') }}</p>
                    <div class="mt-3 space-y-1.5 text-xs text-muted">
                        <div class="flex justify-between">
                            <span>Vehículos activos</span>
                            <span class="font-medium text-base">{{ $stats['activos'] }} / {{ $plan?->max_vehiculos }}</span>
                        </div>
                        @if($plan?->vehiculos_destacados)
                            <div class="flex justify-between">
                                <span>Destacados</span>
                                <span class="font-medium text-base">{{ $plan->vehiculos_destacados }} disponibles</span>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-base text-red-400">Sin suscripción activa.</p>
                    <a href="#" class="btn-primary mt-3 text-xs">Contratar plan</a>
                @endif
            </div>

            {{-- Top vehículos por vistas --}}
            <div class="bg-card border border-base rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-base">
                    <h3 class="font-semibold text-base">Más vistos</h3>
                </div>
                @if($vehiculosDestacados->isEmpty())
                    <p class="text-base text-muted text-center py-8">Sin datos aún.</p>
                @else
                    <div class="divide-y divide-base">
                        @foreach($vehiculosDestacados as $v)
                            <div class="flex items-center gap-3 px-5 py-3">
                                <div class="w-10 h-10 rounded-lg bg-card2 overflow-hidden shrink-0">
                                    @if($v->fotoPrincipal)
                                        <img src="{{ $v->fotoPrincipal->url }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-base truncate">{{ $v->anio }} {{ $v->marca }} {{ $v->modelo }}</p>
                                    <p class="text-xs text-muted">{{ number_format($v->vistas) }} vistas</p>
                                </div>
                                <a href="{{ route('agencia.vehiculos.edit', $v) }}"
                                   class="text-xs text-brand-orange hover:underline shrink-0">Editar</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Acciones rápidas --}}
            <a href="{{ route('agencia.vehiculos.create') }}" class="btn-primary w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Publicar nuevo vehículo
            </a>
        </div>
    </div>

</x-agencia-layout>
