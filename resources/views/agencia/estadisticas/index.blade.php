<x-agencia-layout title="Estadísticas">

    {{-- KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Vistas totales',    'valor' => number_format($stats['vistas_total']),    'color' => 'text-blue-400'],
            ['label' => 'Leads totales',     'valor' => number_format($stats['leads_total']),     'color' => 'text-brand-orange'],
            ['label' => 'Leads ganados',     'valor' => number_format($stats['leads_ganados']),   'color' => 'text-green-400'],
            ['label' => 'Vehículos activos', 'valor' => number_format($stats['vehiculos_activos']),'color' => 'text-base'],
        ] as $s)
            <div class="bg-card border border-base rounded-2xl p-5">
                <p class="text-xs text-muted mb-2">{{ $s['label'] }}</p>
                <p class="text-3xl font-black {{ $s['color'] }}">{{ $s['valor'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Leads por mes --}}
        <div class="bg-card border border-base rounded-2xl p-6">
            <h3 class="font-semibold text-base mb-5">Leads últimos 6 meses</h3>
            @if($leadsPorMes->isEmpty())
                <p class="text-base text-muted text-center py-8">Sin datos aún.</p>
            @else
                @php $maxLeads = $leadsPorMes->max() ?: 1; @endphp
                <div class="space-y-3">
                    @foreach($leadsPorMes as $mes => $total)
                        @php
                            $fecha = \Carbon\Carbon::createFromFormat('Y-m', $mes);
                            $pct   = round(($total / $maxLeads) * 100);
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-muted w-16 shrink-0">{{ $fecha->translatedFormat('M Y') }}</span>
                            <div class="flex-1 bg-card2 rounded-full h-2">
                                <div class="bg-brand-orange h-2 rounded-full transition-all"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs font-semibold text-base w-6 text-right shrink-0">{{ $total }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Leads por tipo de contacto --}}
        <div class="bg-card border border-base rounded-2xl p-6">
            <h3 class="font-semibold text-base mb-5">Canal de contacto preferido</h3>
            @if($leadsPorTipo->isEmpty())
                <p class="text-base text-muted text-center py-8">Sin datos aún.</p>
            @else
                @php
                    $totalTipo = $leadsPorTipo->sum();
                    $colores = ['whatsapp' => 'bg-green-400', 'llamada' => 'bg-blue-400', 'email' => 'bg-yellow-400', 'visita' => 'bg-purple-400'];
                @endphp
                <div class="space-y-3">
                    @foreach($leadsPorTipo as $tipo => $total)
                        @php $pct = $totalTipo ? round(($total / $totalTipo) * 100) : 0; @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-muted w-20 shrink-0 capitalize">{{ $tipo }}</span>
                            <div class="flex-1 bg-card2 rounded-full h-2">
                                <div class="{{ $colores[$tipo] ?? 'bg-brand-orange' }} h-2 rounded-full"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs font-semibold text-base w-8 text-right shrink-0">{{ $pct }}%</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Top vehículos por vistas --}}
        <div class="bg-card border border-base rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-base">
                <h3 class="font-semibold text-base">Top vehículos por vistas</h3>
            </div>
            @if($topVistas->isEmpty())
                <p class="text-base text-muted text-center py-8">Sin datos.</p>
            @else
                <div class="divide-y divide-base">
                    @foreach($topVistas as $i => $v)
                        <div class="flex items-center gap-4 px-6 py-3">
                            <span class="text-lg font-black text-muted w-5 shrink-0">{{ $i + 1 }}</span>
                            <div class="w-10 h-10 rounded-lg bg-card2 overflow-hidden shrink-0">
                                @if($v->fotoPrincipal)
                                    <img src="{{ $v->fotoPrincipal->url }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-base truncate">{{ $v->anio }} {{ $v->marca }} {{ $v->modelo }}</p>
                                <p class="text-xs text-muted">{{ $v->precio_formateado }}</p>
                            </div>
                            <span class="text-sm font-bold text-blue-400 shrink-0">{{ number_format($v->vistas) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Top vehículos por leads --}}
        <div class="bg-card border border-base rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-base">
                <h3 class="font-semibold text-base">Top vehículos por leads</h3>
            </div>
            @if($topLeads->isEmpty())
                <p class="text-base text-muted text-center py-8">Sin datos.</p>
            @else
                <div class="divide-y divide-base">
                    @foreach($topLeads as $i => $v)
                        <div class="flex items-center gap-4 px-6 py-3">
                            <span class="text-lg font-black text-muted w-5 shrink-0">{{ $i + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-base truncate">{{ $v->anio }} {{ $v->marca }} {{ $v->modelo }}</p>
                                <p class="text-xs text-muted">{{ $v->precio_formateado }}</p>
                            </div>
                            <span class="text-sm font-bold text-brand-orange shrink-0">{{ $v->leads_count }} leads</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-agencia-layout>
