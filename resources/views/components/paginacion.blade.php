@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-4" aria-label="Paginación">

        {{-- Info --}}
        <p class="text-sm text-muted hidden sm:block">
            Mostrando
            <span class="font-medium text-base">{{ $paginator->firstItem() }}</span>–<span class="font-medium text-base">{{ $paginator->lastItem() }}</span>
            de
            <span class="font-medium text-base">{{ $paginator->total() }}</span>
        </p>

        {{-- Botones --}}
        <div class="flex items-center gap-1 flex-wrap">

            {{-- Anterior --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-muted bg-card border border-base rounded-lg cursor-not-allowed opacity-50">
                    ← Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-2 text-sm text-base bg-card border border-base rounded-lg hover:border-brand-orange/40 transition-colors">
                    ← Anterior
                </a>
            @endif

            {{-- Páginas --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-2 text-sm text-muted">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-2 text-sm font-semibold bg-brand-orange text-white rounded-lg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-2 text-sm text-base bg-card border border-base rounded-lg hover:border-brand-orange/40 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Siguiente --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-2 text-sm text-base bg-card border border-base rounded-lg hover:border-brand-orange/40 transition-colors">
                    Siguiente →
                </a>
            @else
                <span class="px-3 py-2 text-sm text-muted bg-card border border-base rounded-lg cursor-not-allowed opacity-50">
                    Siguiente →
                </span>
            @endif
        </div>
    </nav>
@endif
