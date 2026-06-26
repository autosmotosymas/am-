<x-vendedor-layout :title="'Editar ' . $vehiculo->marca . ' ' . $vehiculo->modelo" :back="route('vendedor.vehiculos.index', $agencia)">

<form method="POST" action="{{ route('vendedor.vehiculos.update', [$agencia, $vehiculo]) }}"
      x-data="capturaForm()"
      class="px-4 pt-5 space-y-6 pb-4">
    @csrf
    @method('PUT')

    {{-- ── TIPO ── --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Tipo de vehículo</p>
        <div class="flex gap-2 overflow-x-auto pb-1">
            @foreach(['auto' => '🚗 Auto', 'moto' => '🏍 Moto', 'camioneta' => '🚙 Camioneta', 'camion' => '🚛 Camión', 'otro' => '🚐 Otro'] as $val => $label)
                <label class="shrink-0 cursor-pointer">
                    <input type="radio" name="tipo" value="{{ $val }}"
                           {{ old('tipo', $vehiculo->tipo) === $val ? 'checked' : '' }}
                           class="sr-only peer">
                    <span class="block px-4 py-2 rounded-xl text-sm border border-white/10 text-gray-400
                                 peer-checked:bg-brand-orange peer-checked:border-brand-orange peer-checked:text-white
                                 transition-all">
                        {{ $label }}
                    </span>
                </label>
            @endforeach
        </div>
        @error('tipo') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- ── DATOS BÁSICOS ── --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Datos básicos</p>
        <div class="space-y-3">

            <x-selector-vehiculo
                :catalogo="$catalogo"
                tipo="{{ old('tipo', $vehiculo->tipo) }}"
                oldMarca="{{ old('marca', $vehiculo->marca) }}"
                oldModelo="{{ old('modelo', $vehiculo->modelo) }}"
                oldAnio="{{ old('anio', $vehiculo->anio) }}"
            />

            <div>
                <label class="block text-xs text-gray-500 mb-1">Color *</label>
                <input type="text" name="color" value="{{ old('color', $vehiculo->color) }}" required
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                       placeholder="Blanco" autocomplete="off">
                @error('color') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Versión / Trim</label>
                <input type="text" name="version" value="{{ old('version', $vehiculo->version) }}"
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                       placeholder="SE CVT, XLE 4x4…" autocomplete="off">
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">VIN <span class="text-gray-600">(opcional)</span></label>
                <input type="text" name="vin" value="{{ old('vin', $vehiculo->vin) }}"
                       maxlength="17"
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors font-mono placeholder-gray-600"
                       placeholder="17 caracteres">
            </div>
        </div>
    </div>

    {{-- ── MECÁNICA ── --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Mecánica</p>
        <div class="space-y-3">

            <div>
                <label class="block text-xs text-gray-500 mb-2">Transmisión *</label>
                <div class="flex gap-2">
                    @foreach(['manual' => 'Manual', 'automatica' => 'Auto', 'cvt' => 'CVT'] as $val => $label)
                        <label class="flex-1 cursor-pointer text-center">
                            <input type="radio" name="transmision" value="{{ $val }}"
                                   {{ old('transmision', $vehiculo->transmision) === $val ? 'checked' : '' }}
                                   class="sr-only peer">
                            <span class="block py-2.5 rounded-xl text-sm border border-white/10 text-gray-400
                                         peer-checked:bg-brand-orange peer-checked:border-brand-orange peer-checked:text-white
                                         transition-all">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-2">Combustible *</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['gasolina' => 'Gasolina', 'diesel' => 'Diésel', 'electrico' => 'Eléctrico', 'hibrido' => 'Híbrido', 'gas' => 'Gas'] as $val => $label)
                        <label class="cursor-pointer text-center">
                            <input type="radio" name="combustible" value="{{ $val }}"
                                   {{ old('combustible', $vehiculo->combustible) === $val ? 'checked' : '' }}
                                   class="sr-only peer">
                            <span class="block py-2 rounded-xl text-xs border border-white/10 text-gray-400
                                         peer-checked:bg-brand-orange/20 peer-checked:border-brand-orange peer-checked:text-brand-orange
                                         transition-all">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Kilometraje *</label>
                    <input type="number" name="kilometraje" value="{{ old('kilometraje', $vehiculo->kilometraje) }}"
                           min="0" required
                           class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors"
                           placeholder="45000">
                    @error('kilometraje') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Precio (MXN) *</label>
                    <input type="number" name="precio" value="{{ old('precio', $vehiculo->precio) }}"
                           min="0" step="500" required
                           class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors"
                           placeholder="150000">
                    @error('precio') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ── NOTAS ── --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Notas</p>
        <textarea name="notas" rows="3"
                  class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors resize-none placeholder-gray-600"
                  placeholder="Estado del vehículo, detalles a revisar, observaciones…">{{ old('notas', $vehiculo->descripcion) }}</textarea>
    </div>

    <div class="pt-2">
        <button type="submit"
                class="w-full bg-brand-orange hover:bg-brand-orange2 text-white font-semibold py-4 rounded-2xl text-base transition-colors active:scale-95">
            Guardar cambios
        </button>
        <a href="{{ route('vendedor.vehiculos.index', $agencia) }}"
           class="block text-center text-sm text-gray-500 mt-3 py-2">
            Cancelar
        </a>
    </div>

</form>

@push('scripts')
<script>
function capturaForm() {
    return {}
}
</script>
@endpush

</x-vendedor-layout>
