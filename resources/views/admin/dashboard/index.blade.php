<x-admin-layout title="Dashboard">

    {{-- KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label'=>'Agencias activas',   'valor'=>$stats['agencias_activas'],   'total'=>$stats['agencias'],          'color'=>'text-brand-orange'],
            ['label'=>'Vehículos públicos',  'valor'=>$stats['vehiculos_publicos'],  'total'=>$stats['vehiculos'],         'color'=>'text-blue-400'],
            ['label'=>'Suscripciones activas','valor'=>$stats['suscripciones'],      'total'=>null,                        'color'=>'text-green-400'],
            ['label'=>'Leads hoy',           'valor'=>$stats['leads_hoy'],           'total'=>null,                        'color'=>'text-yellow-400'],
        ] as $s)
            <div class="bg-card border border-base rounded-2xl p-5">
                <p class="text-xs text-muted mb-2">{{ $s['label'] }}</p>
                <p class="text-3xl font-black {{ $s['color'] }}">{{ number_format($s['valor']) }}</p>
                @if($s['total'] !== null)
                    <p class="text-xs text-muted mt-1">de {{ number_format($s['total']) }} total</p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Agencias recientes --}}
        <div class="lg:col-span-2 bg-card border border-base rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-base">
                <h2 class="font-semibold text-base">Agencias recientes</h2>
                <a href="{{ route('admin.agencias.index') }}" class="text-xs text-brand-orange hover:underline">Ver todas</a>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-muted uppercase border-b border-base">
                        <th class="text-left px-5 py-3">Agencia</th>
                        <th class="text-center px-4 py-3 hidden md:table-cell">Plan</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base">
                    @foreach($agenciasRecientes as $ag)
                        <tr class="hover:bg-card2 transition-colors">
                            <td class="px-5 py-3">
                                <p class="font-medium text-base">{{ $ag->nombre }}</p>
                                <p class="text-xs text-muted">{{ $ag->ciudad }}</p>
                            </td>
                            <td class="px-4 py-3 text-center hidden md:table-cell">
                                <span class="text-xs text-muted">{{ $ag->suscripcionActiva?->plan?->nombre ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs px-2 py-0.5 rounded-full border
                                    {{ $ag->activo ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                                    {{ $ag->activo ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.agencias.show', $ag) }}"
                                   class="text-xs text-brand-orange hover:underline">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Panel derecho --}}
        <div class="space-y-4">

            {{-- Suscripciones por vencer --}}
            <div class="bg-card border border-yellow-500/20 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-yellow-500/20 flex items-center gap-2">
                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    <h3 class="font-semibold text-yellow-400 text-sm">Vencen en 7 días</h3>
                </div>
                @if($suscripcionesPorVencer->isEmpty())
                    <p class="text-xs text-muted text-center py-6">Ninguna por vencer.</p>
                @else
                    <div class="divide-y divide-base">
                        @foreach($suscripcionesPorVencer as $sus)
                            <div class="px-5 py-3">
                                <p class="text-sm font-medium text-base truncate">{{ $sus->agencia?->nombre }}</p>
                                <p class="text-xs text-yellow-400">
                                    Vence {{ $sus->fecha_vencimiento->diffForHumans() }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Leads de hoy --}}
            <div class="bg-card border border-base rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-base">
                    <h3 class="font-semibold text-base">Leads de hoy</h3>
                </div>
                @if($leadsHoy->isEmpty())
                    <p class="text-xs text-muted text-center py-6">Sin leads hoy.</p>
                @else
                    <div class="divide-y divide-base">
                        @foreach($leadsHoy->take(5) as $lead)
                            <div class="px-5 py-3">
                                <p class="text-xs font-medium text-base">{{ $lead->nombre }}</p>
                                <p class="text-xs text-muted truncate">
                                    {{ $lead->vehiculo?->marca }} {{ $lead->vehiculo?->modelo }}
                                    · {{ $lead->vehiculo?->agencia?->nombre }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Acciones rápidas --}}
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.agencias.create') }}" class="btn-primary justify-center text-sm">
                    + Nueva agencia
                </a>
                <a href="{{ route('admin.suscripciones.create') }}" class="btn-outline justify-center text-sm">
                    + Suscripción manual
                </a>
            </div>
        </div>
    </div>

</x-admin-layout>
