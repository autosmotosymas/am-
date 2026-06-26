@if($agencia)
<div class="bg-card border border-base rounded-2xl p-5">
    <p class="text-xs font-semibold text-muted uppercase tracking-widest mb-3">Agencia</p>

    <a href="{{ route('agencia.show', $agencia) }}"
       class="flex items-center gap-3 group mb-4">
        @if($agencia->logo)
            <img src="{{ asset('storage/' . $agencia->logo) }}"
                 alt="{{ $agencia->nombre }}"
                 class="w-12 h-12 rounded-xl object-cover border border-base">
        @else
            <div class="w-12 h-12 rounded-xl bg-brand-orange/10 border border-brand-orange/20 flex items-center justify-center text-brand-orange font-bold text-lg">
                {{ strtoupper(substr($agencia->nombre, 0, 1)) }}
            </div>
        @endif

        <div class="flex-1 min-w-0">
            <p class="font-semibold text-base group-hover:text-brand-orange transition-colors truncate">
                {{ $agencia->nombre }}
                @if($agencia->verificada)
                    <span class="text-brand-orange text-xs">✓</span>
                @endif
            </p>
            <p class="text-sm text-muted">{{ $agencia->ciudad }}, {{ $agencia->estado }}</p>
        </div>
        <svg class="w-4 h-4 text-muted shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
    </a>

    <div class="space-y-2 text-base border-t border-base pt-4">
        @if($agencia->telefono)
            <a href="tel:{{ preg_replace('/\D/', '', $agencia->telefono) }}"
               class="flex items-center gap-2 text-muted hover:text-brand-orange transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                </svg>
                {{ $agencia->telefono }}
            </a>
        @endif

        @if($agencia->whatsapp)
            <a href="https://wa.me/52{{ preg_replace('/\D/', '', $agencia->whatsapp) }}?text={{ urlencode('Hola, vi el ' . $titulo . ' en AutosMotosYMás y me interesa.') }}"
               target="_blank" rel="noopener"
               class="flex items-center gap-2 text-muted hover:text-green-400 transition-colors">
                <svg class="w-4 h-4 shrink-0 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                WhatsApp
            </a>
        @endif

        @if($agencia->direccion)
            <p class="flex items-start gap-2 text-muted">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>
                {{ $agencia->direccion }}
            </p>
        @endif
    </div>

    <a href="{{ route('agencia.show', $agencia) }}"
       class="btn-outline w-full justify-center mt-4 text-sm">
        Ver más de esta agencia
    </a>
</div>
@endif
