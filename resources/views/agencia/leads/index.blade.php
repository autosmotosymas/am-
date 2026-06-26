<x-agencia-layout title="Leads">

    {{-- Tabs de filtro por status --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach([
            ''               => ['label' => 'Todos',      'count' => array_sum($conteos)],
            'nuevo'          => ['label' => 'Nuevos',     'count' => $conteos['nuevo']],
            'en_proceso'     => ['label' => 'En proceso', 'count' => $conteos['en_proceso']],
            'cerrado_ganado' => ['label' => 'Ganados',    'count' => $conteos['cerrado_ganado']],
            'cerrado_perdido'=> ['label' => 'Cerrados',   'count' => $conteos['cerrado_perdido']],
        ] as $val => $info)
            <a href="{{ route('agencia.leads.index', $val ? ['status' => $val] : []) }}"
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium border transition-colors
                      {{ request('status', '') === $val
                          ? 'bg-brand-orange text-white border-brand-orange'
                          : 'bg-card border-base text-muted hover:border-brand-orange/40 hover:text-base' }}">
                {{ $info['label'] }}
                <span class="text-xs px-1.5 py-0.5 rounded-full
                    {{ request('status', '') === $val ? 'bg-white/20' : 'bg-card2' }}">
                    {{ $info['count'] }}
                </span>
            </a>
        @endforeach
    </div>

    @if($leads->isEmpty())
        <div class="bg-card border border-base rounded-2xl text-center py-16">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
            </svg>
            <p class="text-base text-muted">Sin leads en esta categoría.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($leads as $lead)
                <div id="lead-{{ $lead->id }}"
                     class="bg-card border border-base rounded-2xl p-5
                            {{ $lead->status === 'nuevo' ? 'border-l-2 border-l-blue-400' : '' }}">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">

                        {{-- Avatar inicial --}}
                        <div class="w-10 h-10 rounded-xl bg-brand-orange/10 flex items-center justify-center text-brand-orange font-bold shrink-0">
                            {{ strtoupper(substr($lead->nombre, 0, 1)) }}
                        </div>

                        {{-- Info principal --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <p class="font-semibold text-base">{{ $lead->nombre }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full border
                                    @if($lead->status === 'nuevo') bg-blue-500/10 text-blue-400 border-blue-500/20
                                    @elseif($lead->status === 'en_proceso') bg-yellow-500/10 text-yellow-400 border-yellow-500/20
                                    @elseif($lead->status === 'cerrado_ganado') bg-green-500/10 text-green-400 border-green-500/20
                                    @else bg-gray-500/10 text-gray-400 border-gray-500/20 @endif">
                                    {{ ['nuevo'=>'Nuevo','en_proceso'=>'En proceso','cerrado_ganado'=>'Ganado','cerrado_perdido'=>'Cerrado'][$lead->status] }}
                                </span>
                                <span class="text-xs text-muted">{{ $lead->created_at->diffForHumans() }}</span>
                            </div>

                            {{-- Vehículo --}}
                            @if($lead->vehiculo)
                                <a href="{{ route('vehiculo.show', $lead->vehiculo) }}" target="_blank"
                                   class="text-xs text-brand-orange hover:underline">
                                    {{ $lead->vehiculo->anio }} {{ $lead->vehiculo->marca }} {{ $lead->vehiculo->modelo }}
                                </a>
                            @endif

                            {{-- Contacto --}}
                            <div class="flex flex-wrap gap-4 mt-3 text-base">
                                <a href="tel:{{ preg_replace('/\D/', '', $lead->telefono) }}"
                                   class="flex items-center gap-1.5 text-muted hover:text-brand-orange transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                    </svg>
                                    {{ $lead->telefono }}
                                </a>
                                @if($lead->tipo === 'whatsapp')
                                    <a href="https://wa.me/52{{ preg_replace('/\D/', '', $lead->telefono) }}"
                                       target="_blank"
                                       class="flex items-center gap-1.5 text-green-400 hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        WhatsApp
                                    </a>
                                @endif
                                @if($lead->email)
                                    <a href="mailto:{{ $lead->email }}"
                                       class="flex items-center gap-1.5 text-muted hover:text-brand-orange transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                        </svg>
                                        {{ $lead->email }}
                                    </a>
                                @endif
                            </div>

                            {{-- Mensaje --}}
                            @if($lead->mensaje)
                                <p class="mt-3 text-base text-muted bg-card2 rounded-xl px-4 py-3 leading-relaxed">
                                    "{{ $lead->mensaje }}"
                                </p>
                            @endif
                        </div>

                        {{-- Acción cambiar status --}}
                        @if(!in_array($lead->status, ['cerrado_ganado', 'cerrado_perdido']))
                            <form method="POST" action="{{ route('agencia.leads.leer', $lead) }}" class="shrink-0">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-xs px-3 py-2 rounded-xl border border-base hover:border-brand-orange/40 text-muted hover:text-brand-orange transition-colors whitespace-nowrap">
                                    {{ $lead->status === 'nuevo' ? '→ En proceso' : '→ Marcar ganado' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($leads->hasPages())
            <div class="mt-6">
                {{ $leads->links('components.paginacion') }}
            </div>
        @endif
    @endif

</x-agencia-layout>
