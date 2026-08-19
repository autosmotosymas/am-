<x-agencia-layout title="Solicitar Certificación">

<div class="max-w-xl">
    <form method="POST" action="{{ route('agencia.certificaciones.store') }}" class="space-y-5">
        @csrf

        <div class="bg-card border border-base rounded-2xl p-6 space-y-5">

            <div>
                <label class="block text-xs text-muted mb-1.5">Vehículo *</label>
                <select name="vehiculo_id" required
                        class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange">
                    <option value="">— Selecciona un vehículo —</option>
                    @foreach($vehiculos as $v)
                        <option value="{{ $v->id }}" {{ old('vehiculo_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->anio }} {{ $v->marca }} {{ $v->modelo }} ({{ $v->color }})
                        </option>
                    @endforeach
                </select>
                @error('vehiculo_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs text-muted mb-1.5">Verificador preferido <span class="text-muted opacity-60">(opcional)</span></label>
                <select name="verificador_id"
                        class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange">
                    <option value="">— El administrador asignará uno —</option>
                    @foreach($verificadores as $ver)
                        <option value="{{ $ver->id }}" {{ old('verificador_id') == $ver->id ? 'selected' : '' }}>
                            {{ $ver->nombre }}{{ $ver->zona ? ' — ' . $ver->zona : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs text-muted mb-1.5">Fecha de inspección propuesta *</label>
                <input type="date" name="fecha_inspeccion" required
                       value="{{ old('fecha_inspeccion', date('Y-m-d', strtotime('+3 days'))) }}"
                       min="{{ date('Y-m-d') }}"
                       class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange">
                @error('fecha_inspeccion') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs text-muted mb-1.5">Observaciones o notas <span class="text-muted opacity-60">(opcional)</span></label>
                <textarea name="observaciones" rows="3"
                          class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange resize-none"
                          placeholder="Detalles adicionales para el verificador…">{{ old('observaciones') }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Enviar solicitud</button>
            <a href="{{ route('agencia.certificaciones.index') }}" class="btn-ghost">Cancelar</a>
        </div>
    </form>
</div>

</x-agencia-layout>
