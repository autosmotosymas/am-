@php
    $editando = isset($vehiculo);
    $titulo = $editando ? "Editar: {$vehiculo->anio} {$vehiculo->marca} {$vehiculo->modelo}" : 'Nuevo vehículo';
@endphp

<x-agencia-layout :title="$titulo">

    <div class="max-w-3xl">

        <form method="POST"
              action="{{ $editando ? route('agencia.vehiculos.update', $vehiculo) : route('agencia.vehiculos.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if($editando) @method('PUT') @endif

            {{-- ── DATOS PRINCIPALES ── --}}
            <div class="bg-card border border-base rounded-2xl p-6 space-y-5">
                <h2 class="font-semibold text-base">Datos del vehículo</h2>

                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-semibold text-base mb-2">Tipo</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['auto' => 'Auto', 'moto' => 'Moto', 'camioneta' => 'Camioneta', 'camion' => 'Camión', 'otro' => 'Otro'] as $val => $label)
                            <label class="flex items-center gap-1.5 cursor-pointer px-3 py-2 rounded-lg border border-base hover:border-brand-orange/40 transition-colors has-[:checked]:border-brand-orange has-[:checked]:bg-brand-orange/5">
                                <input type="radio" name="tipo" value="{{ $val }}"
                                       {{ old('tipo', $vehiculo->tipo ?? 'auto') === $val ? 'checked' : '' }}
                                       class="accent-brand-orange">
                                <span class="text-sm text-base">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('tipo') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Marca / Modelo / Año --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Marca *</label>
                        <input type="text" name="marca" value="{{ old('marca', $vehiculo->marca ?? '') }}" required
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('marca') border-red-500/60 @enderror"
                               placeholder="Toyota">
                        @error('marca') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Modelo *</label>
                        <input type="text" name="modelo" value="{{ old('modelo', $vehiculo->modelo ?? '') }}" required
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('modelo') border-red-500/60 @enderror"
                               placeholder="Corolla">
                        @error('modelo') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Año *</label>
                        <input type="number" name="anio" value="{{ old('anio', $vehiculo->anio ?? date('Y')) }}"
                               min="1970" max="{{ date('Y') + 1 }}" required
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('anio') border-red-500/60 @enderror">
                        @error('anio') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Versión --}}
                <div>
                    <label class="block text-sm font-semibold text-base mb-1.5">Versión / Trim</label>
                    <input type="text" name="version" value="{{ old('version', $vehiculo->version ?? '') }}"
                           class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors"
                           placeholder="SE CVT, XLE 4x4…">
                </div>

                {{-- Precio --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Precio (MXN) *</label>
                        <input type="number" name="precio" value="{{ old('precio', $vehiculo->precio ?? '') }}"
                               min="0" step="500" required
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('precio') border-red-500/60 @enderror"
                               placeholder="150000">
                        @error('precio') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="precio_negociable" value="1"
                                   {{ old('precio_negociable', $vehiculo->precio_negociable ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 accent-brand-orange">
                            <span class="text-sm text-base">Precio negociable</span>
                        </label>
                    </div>
                </div>

                {{-- Kilometraje --}}
                <div>
                    <label class="block text-sm font-semibold text-base mb-1.5">Kilometraje *</label>
                    <input type="number" name="kilometraje" value="{{ old('kilometraje', $vehiculo->kilometraje ?? 0) }}"
                           min="0" required
                           class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('kilometraje') border-red-500/60 @enderror"
                           placeholder="45000">
                    @error('kilometraje') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Transmisión / Combustible --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Transmisión *</label>
                        <select name="transmision"
                                class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                            @foreach(['manual' => 'Manual', 'automatica' => 'Automática', 'cvt' => 'CVT'] as $val => $label)
                                <option value="{{ $val }}" {{ old('transmision', $vehiculo->transmision ?? 'automatica') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Combustible *</label>
                        <select name="combustible"
                                class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                            @foreach(['gasolina' => 'Gasolina', 'diesel' => 'Diésel', 'electrico' => 'Eléctrico', 'hibrido' => 'Híbrido', 'gas' => 'Gas'] as $val => $label)
                                <option value="{{ $val }}" {{ old('combustible', $vehiculo->combustible ?? 'gasolina') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Color / Puertas / Cilindros / Motor --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Color *</label>
                        <input type="text" name="color" value="{{ old('color', $vehiculo->color ?? '') }}" required
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors"
                               placeholder="Blanco">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Puertas</label>
                        <input type="number" name="puertas" value="{{ old('puertas', $vehiculo->puertas ?? '') }}"
                               min="2" max="6"
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors"
                               placeholder="4">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Cilindros</label>
                        <input type="number" name="cilindros" value="{{ old('cilindros', $vehiculo->cilindros ?? '') }}"
                               min="1" max="16"
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors"
                               placeholder="4">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Motor</label>
                        <input type="text" name="motor" value="{{ old('motor', $vehiculo->motor ?? '') }}"
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors"
                               placeholder="2.0L">
                    </div>
                </div>

                {{-- Ciudad / Estado --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Ciudad</label>
                        <input type="text" name="ciudad" value="{{ old('ciudad', $vehiculo->ciudad ?? 'Guadalajara') }}"
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Estado</label>
                        <input type="text" name="estado" value="{{ old('estado', $vehiculo->estado ?? 'Jalisco') }}"
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                    </div>
                </div>

                {{-- VIN / Placas --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">VIN</label>
                        <input type="text" name="vin" value="{{ old('vin', $vehiculo->vin ?? '') }}"
                               maxlength="17"
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors font-mono"
                               placeholder="1HGCM82633A004352">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-base mb-1.5">Placas</label>
                        <input type="text" name="placas" value="{{ old('placas', $vehiculo->placas ?? '') }}"
                               class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors font-mono"
                               placeholder="ABC-1234">
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-semibold text-base mb-1.5">Descripción</label>
                    <textarea name="descripcion" rows="4"
                              class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors resize-none"
                              placeholder="Describe el estado del vehículo, equipamiento, historial de mantenimiento…">{{ old('descripcion', $vehiculo->descripcion ?? '') }}</textarea>
                </div>

                {{-- Status / Destacado (solo edición) --}}
                @if($editando)
                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-base">
                        <div>
                            <label class="block text-sm font-semibold text-base mb-1.5">Status</label>
                            <select name="status"
                                    class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                                @foreach(['disponible' => 'Activo', 'inactivo' => 'Pausado', 'apartado' => 'Apartado', 'vendido' => 'Vendido'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('status', $vehiculo->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($plan?->vehiculos_destacados)
                            <div class="flex items-end pb-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="destacado" value="1"
                                           {{ old('destacado', $vehiculo->destacado) ? 'checked' : '' }}
                                           class="w-4 h-4 accent-brand-orange">
                                    <span class="text-sm text-base">Marcar como destacado</span>
                                </label>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ── FOTOS ── --}}
            <div class="bg-card border border-base rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-base">Fotos</h2>
                    <span class="text-xs text-muted">Máx. {{ $plan?->max_fotos_por_vehiculo ?? 8 }} fotos · 5 MB c/u</span>
                </div>

                {{-- Fotos actuales (edición) --}}
                @if($editando && $vehiculo->fotos->isNotEmpty())
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                        @foreach($vehiculo->fotos as $foto)
                            <div class="relative group aspect-square rounded-xl overflow-hidden border border-base">
                                <img src="{{ $foto->url }}" class="w-full h-full object-cover">
                                <label class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    <input type="checkbox" name="fotos_eliminar[]" value="{{ $foto->id }}"
                                           class="accent-red-400 w-4 h-4">
                                    <span class="text-white text-xs ml-1">Eliminar</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-muted">Marca las fotos que quieras eliminar y guarda.</p>
                @endif

                {{-- Upload nuevas fotos --}}
                <div x-data="{ files: [] }">
                    <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-base rounded-2xl cursor-pointer hover:border-brand-orange/50 hover:bg-brand-orange/5 transition-colors">
                        <svg class="w-8 h-8 text-muted mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <p class="text-base text-muted">Arrastra fotos aquí o <span class="text-brand-orange">selecciona</span></p>
                        <p class="text-xs text-gray-600 mt-1" x-text="files.length ? files.length + ' archivo(s) seleccionado(s)' : 'JPG, PNG, WebP'"></p>
                        <input type="file" name="fotos[]" multiple accept="image/*" class="hidden"
                               @change="files = Array.from($event.target.files)">
                    </label>
                </div>
                @error('fotos.*') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            {{-- Botones --}}
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">
                    {{ $editando ? 'Guardar cambios' : 'Publicar vehículo' }}
                </button>
                <a href="{{ route('agencia.vehiculos.index') }}" class="btn-outline">Cancelar</a>
            </div>

        </form>
    </div>

</x-agencia-layout>
