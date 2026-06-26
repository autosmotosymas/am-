<x-admin-layout title="Editar certificación">
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.certificaciones.update', $cert) }}"
          class="bg-card border border-base rounded-2xl p-6 space-y-4">
        @csrf @method('PUT')

        {{-- Vehículo (solo lectura al editar) --}}
        <div>
            <label class="block text-sm font-semibold text-base mb-1.5">Vehículo</label>
            <div class="bg-card2 border border-base rounded-xl px-3 py-2.5 text-base text-muted">
                {{ $cert->vehiculo?->anio }} {{ $cert->vehiculo?->marca }} {{ $cert->vehiculo?->modelo }}
                — {{ $cert->vehiculo?->agencia?->nombre ?? '—' }}
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-base mb-1.5">Verificador *</label>
            <select name="verificador_id" required
                    class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('verificador_id') border-red-500/60 @enderror">
                @foreach($verificadores as $ver)
                    <option value="{{ $ver->id }}" {{ old('verificador_id', $cert->verificador_id) == $ver->id ? 'selected' : '' }}>
                        {{ $ver->nombre }} @if($ver->zona)({{ $ver->zona }})@endif
                    </option>
                @endforeach
            </select>
            @error('verificador_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-base mb-1.5">Fecha de inspección *</label>
            <input type="date" name="fecha_inspeccion"
                   value="{{ old('fecha_inspeccion', $cert->fecha_inspeccion->format('Y-m-d')) }}" required
                   class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('fecha_inspeccion') border-red-500/60 @enderror">
            @error('fecha_inspeccion') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-base mb-1.5">Resultado *</label>
                <select name="resultado" required
                        class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                    @foreach(['pendiente' => 'Pendiente', 'aprobado' => 'Aprobado', 'rechazado' => 'Rechazado'] as $val => $label)
                        <option value="{{ $val }}" {{ old('resultado', $cert->resultado) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-base mb-1.5">Puntaje (0-100)</label>
                <input type="number" name="puntaje" value="{{ old('puntaje', $cert->puntaje) }}" min="0" max="100"
                       class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('puntaje') border-red-500/60 @enderror">
                @error('puntaje') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-base mb-1.5">Observaciones</label>
            <textarea name="observaciones" rows="3"
                      class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors resize-none @error('observaciones') border-red-500/60 @enderror">{{ old('observaciones', $cert->observaciones) }}</textarea>
            @error('observaciones') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Guardar cambios</button>
            <a href="{{ route('admin.certificaciones.index') }}" class="btn-outline">Cancelar</a>
        </div>
    </form>

    {{-- Eliminar --}}
    <form method="POST" action="{{ route('admin.certificaciones.destroy', $cert) }}" class="mt-4"
          onsubmit="return confirm('¿Eliminar esta certificación? Esta acción no se puede deshacer.')">
        @csrf @method('DELETE')
        <button class="text-xs text-red-400 hover:underline">Eliminar certificación</button>
    </form>
</div>
</x-admin-layout>
