@props(['nombre', 'label'])

<a href="{{ url()->current() }}?{{ http_build_query(request()->except($nombre)) }}"
   class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full
          bg-brand-orange/10 text-brand-orange border border-brand-orange/30
          hover:bg-brand-orange/20 transition-colors">
    {{ $label }}
    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
    </svg>
</a>
