{{--
  Selector de Marca / Modelo / Año para formularios de captura
  Props: $catalogo (array), $tipo (string 'auto'|'moto'|...), $oldMarca, $oldModelo, $oldAnio
--}}
@props([
    'catalogo'  => [],
    'tipo'      => 'auto',
    'oldMarca'  => '',
    'oldModelo' => '',
    'oldAnio'   => '',
])

@php
    $marcasAutos  = array_keys($catalogo['autos'] ?? []);
    $marcasMotos  = array_keys($catalogo['motos'] ?? []);
    $anioActual   = date('Y');
    $anos = range($anioActual + 1, 1990);
@endphp

<div x-data="selectorVehiculo({{ json_encode($catalogo['autos']) }}, {{ json_encode($catalogo['motos']) }}, '{{ $tipo }}', '{{ $oldMarca }}', '{{ $oldModelo }}')"
     x-init="init()">

    {{-- Marca --}}
    <div class="mb-3">
        <label class="block text-xs text-gray-500 mb-1">Marca *</label>
        <select name="marca" x-model="marca" @change="onMarcaChange()" required
                class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors">
            <option value="" class="bg-[#1a1a1a]">Selecciona una marca…</option>
            <template x-for="m in marcas" :key="m">
                <option :value="m" :selected="m === marca" class="bg-[#1a1a1a]" x-text="m"></option>
            </template>
        </select>
        @error('marca') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Modelo --}}
    <div class="mb-3">
        <label class="block text-xs text-gray-500 mb-1">Modelo *</label>
        <select name="modelo" x-model="modelo" required
                :disabled="modelos.length === 0"
                class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors disabled:opacity-40">
            <option value="" class="bg-[#1a1a1a]">
                <span x-show="marca === ''">Primero selecciona una marca</span>
                <span x-show="marca !== ''">Selecciona un modelo…</span>
            </option>
            <template x-for="m in modelos" :key="m">
                <option :value="m" :selected="m === modelo" class="bg-[#1a1a1a]" x-text="m"></option>
            </template>
        </select>
        @error('modelo') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Año --}}
    <div>
        <label class="block text-xs text-gray-500 mb-1">Año *</label>
        <select name="anio" required
                class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors">
            <option value="" class="bg-[#1a1a1a]">Selecciona el año…</option>
            @foreach($anos as $a)
                <option value="{{ $a }}" {{ $oldAnio == $a ? 'selected' : '' }} class="bg-[#1a1a1a]">{{ $a }}</option>
            @endforeach
        </select>
        @error('anio') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>
</div>

@once
@push('scripts')
<script>
function selectorVehiculo(catalogoAutos, catalogoMotos, tipoInicial, oldMarca, oldModelo) {
    return {
        marca:   oldMarca,
        modelo:  oldModelo,
        marcas:  [],
        modelos: [],
        tipo:    tipoInicial,

        init() {
            this.actualizarMarcas();
            if (this.marca) this.actualizarModelos();

            // Escuchar cambios en el radio de tipo
            this.$watch('tipo', () => {
                this.marca  = '';
                this.modelo = '';
                this.actualizarMarcas();
                this.modelos = [];
            });

            // Conectar con el radio de tipo del formulario
            document.querySelectorAll('input[name="tipo"]').forEach(radio => {
                radio.addEventListener('change', (e) => {
                    this.tipo = e.target.value;
                });
            });
        },

        actualizarMarcas() {
            const catalogo = ['moto'].includes(this.tipo) ? catalogoMotos : catalogoAutos;
            this.marcas = Object.keys(catalogo).sort();
        },

        actualizarModelos() {
            const catalogo = ['moto'].includes(this.tipo) ? catalogoMotos : catalogoAutos;
            this.modelos = catalogo[this.marca] ?? [];
            if (!this.modelos.includes(this.modelo)) {
                this.modelo = '';
            }
        },

        onMarcaChange() {
            this.modelo = '';
            this.actualizarModelos();
        }
    }
}
</script>
@endpush
@endonce
