<x-app-layout title="Buscar autos seminuevos en Guadalajara — AutosMotosYMás"
              meta-desc="Encuentra tu auto seminuevo en Guadalajara. Filtra por marca, precio, año y más. Solo agencias verificadas.">

<div class="bg-page min-h-screen">
    <div class="container-amm py-8">

        {{-- Encabezado --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base">
                @if(request('q'))
                    Resultados para "<span class="text-brand-orange">{{ request('q') }}</span>"
                @elseif(request('marca'))
                    {{ request('marca') }}
                @elseif(request('tipo'))
                    {{ ucfirst(request('tipo')) }}s
                @else
                    Buscar autos seminuevos
                @endif
            </h1>
            <p class="text-base text-muted mt-1">
                {{ number_format($vehiculos->total()) }} {{ Str::plural('vehículo', $vehiculos->total()) }} encontrado{{ $vehiculos->total() !== 1 ? 's' : '' }}
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6" x-data="{ filtrosAbiertos: false }">

            {{-- ══════════════ PANEL DE FILTROS ══════════════ --}}
            {{-- Overlay móvil --}}
            <div x-show="filtrosAbiertos"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="filtrosAbiertos = false"
                 class="fixed inset-0 bg-black/50 z-30 lg:hidden">
            </div>

            {{-- Panel --}}
            <aside
                class="w-full lg:w-64 shrink-0
                       fixed inset-y-0 left-0 z-40 bg-card border-r border-base overflow-y-auto
                       lg:static lg:z-auto lg:bg-transparent lg:border-0 lg:overflow-visible
                       transform transition-transform duration-200
                       lg:translate-x-0"
                :class="filtrosAbiertos ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

                <form method="GET" action="{{ route('busqueda') }}" id="form-filtros">

                    {{-- Header filtros (móvil) --}}
                    <div class="flex items-center justify-between p-4 border-b border-base lg:hidden">
                        <span class="font-semibold text-base">Filtros</span>
                        <button type="button" @click="filtrosAbiertos = false" class="btn-ghost w-8 h-8 p-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-4 lg:p-0 space-y-5">

                        {{-- Búsqueda texto --}}
                        <div>
                            <label class="block text-sm font-semibold text-base mb-1.5">Buscar</label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                   placeholder="Marca, modelo, año…"
                                   class="w-full bg-card border border-base text-base rounded-lg px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-500">
                        </div>

                        {{-- Marca --}}
                        <div>
                            <label class="block text-sm font-semibold text-base mb-1.5">Marca</label>
                            <select name="marca"
                                    class="w-full bg-card border border-base text-base rounded-lg px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors">
                                <option value="">Todas las marcas</option>
                                @foreach($marcas as $m)
                                    <option value="{{ $m }}" {{ request('marca') === $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label class="block text-sm font-semibold text-base mb-1.5">Tipo</label>
                            <select name="tipo"
                                    class="w-full bg-card border border-base text-base rounded-lg px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors">
                                <option value="">Todos los tipos</option>
                                @foreach(['auto' => 'Auto', 'moto' => 'Moto', 'camioneta' => 'Camioneta', 'camion' => 'Camión', 'otro' => 'Otro'] as $val => $label)
                                    <option value="{{ $val }}" {{ request('tipo') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Transmisión --}}
                        <div>
                            <label class="block text-sm font-semibold text-base mb-1.5">Transmisión</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['manual' => 'Manual', 'automatica' => 'Automática', 'cvt' => 'CVT'] as $val => $label)
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" name="transmision" value="{{ $val }}"
                                               {{ request('transmision') === $val ? 'checked' : '' }}
                                               class="accent-brand-orange">
                                        <span class="text-base text-base">{{ $label }}</span>
                                    </label>
                                @endforeach
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="transmision" value=""
                                           {{ !request('transmision') ? 'checked' : '' }}
                                           class="accent-brand-orange">
                                    <span class="text-base text-muted">Todas</span>
                                </label>
                            </div>
                        </div>

                        {{-- Combustible --}}
                        <div>
                            <label class="block text-sm font-semibold text-base mb-1.5">Combustible</label>
                            <select name="combustible"
                                    class="w-full bg-card border border-base text-base rounded-lg px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors">
                                <option value="">Todos</option>
                                @foreach(['gasolina' => 'Gasolina', 'diesel' => 'Diésel', 'electrico' => 'Eléctrico', 'hibrido' => 'Híbrido', 'gas' => 'Gas'] as $val => $label)
                                    <option value="{{ $val }}" {{ request('combustible') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Año --}}
                        <div>
                            <label class="block text-sm font-semibold text-base mb-1.5">Año</label>
                            <div class="flex gap-2 items-center">
                                <input type="number" name="anio_min" value="{{ request('anio_min') }}"
                                       min="{{ $anioMin }}" max="{{ $anioMax }}"
                                       placeholder="{{ $anioMin }}"
                                       class="w-full bg-card border border-base text-base rounded-lg px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors">
                                <span class="text-muted text-base">–</span>
                                <input type="number" name="anio_max" value="{{ request('anio_max') }}"
                                       min="{{ $anioMin }}" max="{{ $anioMax }}"
                                       placeholder="{{ $anioMax }}"
                                       class="w-full bg-card border border-base text-base rounded-lg px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors">
                            </div>
                        </div>

                        {{-- Precio --}}
                        <div>
                            <label class="block text-sm font-semibold text-base mb-1.5">Precio (MXN)</label>
                            <div class="flex gap-2 items-center">
                                <input type="number" name="precio_min" value="{{ request('precio_min') }}"
                                       step="5000" placeholder="Mínimo"
                                       class="w-full bg-card border border-base text-base rounded-lg px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors">
                                <span class="text-muted text-base">–</span>
                                <input type="number" name="precio_max" value="{{ request('precio_max') }}"
                                       step="5000" placeholder="Máximo"
                                       class="w-full bg-card border border-base text-base rounded-lg px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors">
                            </div>
                        </div>

                        {{-- Solo certificados --}}
                        <label class="flex items-center gap-2.5 cursor-pointer p-3 rounded-lg border border-base hover:border-brand-orange/40 transition-colors">
                            <input type="checkbox" name="certificado" value="1"
                                   {{ request('certificado') ? 'checked' : '' }}
                                   class="w-4 h-4 accent-brand-orange">
                            <div>
                                <span class="text-sm font-medium text-base block">Solo certificados</span>
                                <span class="text-xs text-muted">Con inspección física aprobada</span>
                            </div>
                        </label>

                        {{-- Botones --}}
                        <div class="flex gap-2 pt-2">
                            <button type="submit" class="btn-primary flex-1 justify-center">
                                Aplicar
                            </button>
                            <a href="{{ route('busqueda') }}" class="btn-outline px-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </form>
            </aside>

            {{-- ══════════════ RESULTADOS ══════════════ --}}
            <div class="flex-1 min-w-0">

                {{-- Barra superior: filtros activos + ordenamiento --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center mb-5">

                    {{-- Botón abrir filtros (móvil) + chips filtros activos --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" @click="filtrosAbiertos = true"
                                class="btn-outline lg:hidden text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/>
                            </svg>
                            Filtros
                            @php
                                $activos = collect(['q','marca','tipo','transmision','combustible','anio_min','anio_max','precio_min','precio_max','certificado'])->filter(fn($k) => request($k))->count();
                            @endphp
                            @if($activos)
                                <span class="bg-brand-orange text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold">{{ $activos }}</span>
                            @endif
                        </button>

                        {{-- Chips de filtros activos --}}
                        @if(request('marca'))
                            <x-chip-filtro nombre="marca" label="{{ request('marca') }}" />
                        @endif
                        @if(request('tipo'))
                            <x-chip-filtro nombre="tipo" label="{{ ucfirst(request('tipo')) }}" />
                        @endif
                        @if(request('transmision'))
                            <x-chip-filtro nombre="transmision" label="{{ ucfirst(request('transmision')) }}" />
                        @endif
                        @if(request('certificado'))
                            <x-chip-filtro nombre="certificado" label="Certificados" />
                        @endif
                    </div>

                    {{-- Ordenamiento --}}
                    <select name="orden" form="form-filtros"
                            onchange="document.getElementById('form-filtros').submit()"
                            class="bg-card border border-base text-base rounded-lg px-3 py-2 focus:outline-none focus:border-brand-orange transition-colors shrink-0">
                        <option value="reciente"   {{ request('orden','reciente') === 'reciente'   ? 'selected' : '' }}>Más recientes</option>
                        <option value="precio_asc" {{ request('orden') === 'precio_asc'  ? 'selected' : '' }}>Precio: menor a mayor</option>
                        <option value="precio_desc"{{ request('orden') === 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                        <option value="km_asc"     {{ request('orden') === 'km_asc'      ? 'selected' : '' }}>Menor kilometraje</option>
                        <option value="anio_desc"  {{ request('orden') === 'anio_desc'   ? 'selected' : '' }}>Año: más nuevo</option>
                    </select>
                </div>

                {{-- Grid de resultados --}}
                @if($vehiculos->isEmpty())
                    <div class="text-center py-24 bg-card border border-base rounded-2xl">
                        <svg class="w-14 h-14 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <p class="font-semibold text-base mb-1">Sin resultados</p>
                        <p class="text-sm text-muted mb-6">Intenta con otros filtros o amplía tu búsqueda.</p>
                        <a href="{{ route('busqueda') }}" class="btn-outline">Ver todos los autos</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                        @foreach($vehiculos as $v)
                            <x-tarjeta-vehiculo :vehiculo="$v" />
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    @if($vehiculos->hasPages())
                        <div class="mt-8">
                            {{ $vehiculos->links('components.paginacion') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

</x-app-layout>
