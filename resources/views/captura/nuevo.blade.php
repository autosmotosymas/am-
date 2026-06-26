<x-captura-layout title="Nuevo vehículo" back="{{ route('captura.index') }}">

<form method="POST" action="{{ route('captura.store') }}"
      enctype="multipart/form-data"
      x-data="capturaForm()"
      class="px-4 pt-5 space-y-6 pb-4">
    @csrf

    {{-- ── FOTOS (primero para la cámara) ── --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Fotos</p>

        {{-- Previews --}}
        <div x-show="previews.length > 0" class="grid grid-cols-3 gap-2 mb-3">
            <template x-for="(src, i) in previews" :key="i">
                <div class="relative aspect-square rounded-xl overflow-hidden bg-white/5">
                    <img :src="src" class="w-full h-full object-cover">
                    <button type="button" @click="removePhoto(i)"
                            class="absolute top-1 right-1 w-6 h-6 bg-black/60 rounded-full flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <span x-show="i === 0"
                          class="absolute bottom-1 left-1 text-[9px] bg-brand-orange text-white px-1.5 py-0.5 rounded-full font-semibold">
                        Principal
                    </span>
                </div>
            </template>
        </div>

        {{-- Botones de cámara / galería --}}
        <div class="grid grid-cols-2 gap-3">
            <label class="flex flex-col items-center gap-2 bg-white/5 border border-white/10 rounded-2xl py-4 cursor-pointer hover:bg-white/8 active:scale-95 transition-all">
                <svg class="w-7 h-7 text-brand-orange" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
                </svg>
                <span class="text-xs text-gray-400">Cámara</span>
                <input type="file" name="fotos[]" multiple accept="image/*"
                       capture="environment" class="hidden"
                       @change="addPhotos($event)">
            </label>

            <label class="flex flex-col items-center gap-2 bg-white/5 border border-white/10 rounded-2xl py-4 cursor-pointer hover:bg-white/8 active:scale-95 transition-all">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
                <span class="text-xs text-gray-400">Galería</span>
                <input type="file" name="fotos[]" multiple accept="image/*"
                       class="hidden"
                       @change="addPhotos($event)">
            </label>
        </div>

        <p x-show="previews.length > 0" class="text-xs text-gray-600 text-center mt-2"
           x-text="previews.length + ' foto(s) seleccionada(s)'"></p>

        @error('fotos.*') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- ── TIPO ── --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Tipo de vehículo</p>
        <div class="flex gap-2 overflow-x-auto pb-1">
            @foreach(['auto' => '🚗 Auto', 'moto' => '🏍 Moto', 'camioneta' => '🚙 Camioneta', 'camion' => '🚛 Camión', 'otro' => '🚐 Otro'] as $val => $label)
                <label class="shrink-0 cursor-pointer">
                    <input type="radio" name="tipo" value="{{ $val }}"
                           {{ old('tipo', 'auto') === $val ? 'checked' : '' }}
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
                tipo="{{ old('tipo', 'auto') }}"
                oldMarca="{{ old('marca') }}"
                oldModelo="{{ old('modelo') }}"
                oldAnio="{{ old('anio', date('Y')) }}"
            />

            <div>
                <label class="block text-xs text-gray-500 mb-1">Color *</label>
                <input type="text" name="color" value="{{ old('color') }}" required
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                       placeholder="Blanco" autocomplete="off">
                @error('color') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Versión / Trim</label>
                <input type="text" name="version" value="{{ old('version') }}"
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                       placeholder="SE CVT, XLE 4x4…" autocomplete="off">
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">VIN <span class="text-gray-600">(opcional)</span></label>
                <input type="text" name="vin" value="{{ old('vin') }}"
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
                                   {{ old('transmision', 'automatica') === $val ? 'checked' : '' }}
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
                                   {{ old('combustible', 'gasolina') === $val ? 'checked' : '' }}
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
                    <input type="number" name="kilometraje" value="{{ old('kilometraje', 0) }}"
                           min="0" required
                           class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors"
                           placeholder="45000">
                    @error('kilometraje') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Precio (MXN) *</label>
                    <input type="number" name="precio" value="{{ old('precio') }}"
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
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Notas para la agencia</p>
        <textarea name="notas" rows="3"
                  class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors resize-none placeholder-gray-600"
                  placeholder="Estado del vehículo, detalles a revisar, observaciones…">{{ old('notas') }}</textarea>
    </div>

    {{-- Submit --}}
    <div class="pt-2">
        <button type="submit"
                class="w-full bg-brand-orange hover:bg-brand-orange2 text-white font-semibold py-4 rounded-2xl text-base transition-colors active:scale-95">
            Guardar vehículo
        </button>
        <a href="{{ route('captura.index') }}"
           class="block text-center text-sm text-gray-500 mt-3 py-2">
            Cancelar
        </a>
    </div>

</form>

@push('scripts')
<script>
function capturaForm() {
    return {
        previews: [],
        files: [],

        addPhotos(event) {
            const nuevos = Array.from(event.target.files);
            nuevos.forEach(file => {
                this.files.push(file);
                const reader = new FileReader();
                reader.onload = e => this.previews.push(e.target.result);
                reader.readAsDataURL(file);
            });
        },

        removePhoto(i) {
            this.previews.splice(i, 1);
            this.files.splice(i, 1);
        }
    }
}
</script>
@endpush

</x-captura-layout>
