<x-admin-layout title="Editar suscripción">
<div class="max-w-md">
    <form method="POST" action="{{ route('admin.suscripciones.update', $sus) }}"
          class="bg-card border border-base rounded-2xl p-6 space-y-4">
        @csrf @method('PUT')

        {{-- Agencia (solo lectura) --}}
        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Agencia</label>
            <div class="bg-card2 border border-base rounded-xl px-3 py-2.5 text-sm text-muted">
                {{ $sus->agencia?->nombre }} — {{ $sus->agencia?->ciudad }}
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Plan *</label>
            <select name="plan_id" required
                    class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('plan_id') border-red-500/60 @enderror">
                @foreach($planes as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id', $sus->plan_id) == $plan->id ? 'selected' : '' }}>
                        {{ $plan->nombre }} — {{ $plan->precio_formateado }}/mes
                    </option>
                @endforeach
            </select>
            @error('plan_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Status *</label>
            <select name="status" required
                    class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
                @foreach(['prueba' => 'Prueba', 'activa' => 'Activa', 'vencida' => 'Vencida', 'cancelada' => 'Cancelada'] as $val => $label)
                    <option value="{{ $val }}" {{ old('status', $sus->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Fecha de vencimiento *</label>
            <input type="date" name="fecha_vencimiento"
                   value="{{ old('fecha_vencimiento', $sus->fecha_vencimiento->format('Y-m-d')) }}" required
                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('fecha_vencimiento') border-red-500/60 @enderror">
            @error('fecha_vencimiento') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Precio pagado ($)</label>
            <input type="number" name="precio_pagado" value="{{ old('precio_pagado', $sus->precio_pagado) }}"
                   min="0" step="0.01"
                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('precio_pagado') border-red-500/60 @enderror">
            @error('precio_pagado') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Guardar cambios</button>
            <a href="{{ route('admin.suscripciones.index') }}" class="btn-outline">Cancelar</a>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.suscripciones.destroy', $sus) }}" class="mt-4"
          onsubmit="return confirm('¿Eliminar esta suscripción permanentemente?')">
        @csrf @method('DELETE')
        <button class="text-xs text-red-400 hover:underline">Eliminar registro</button>
    </form>
</div>
</x-admin-layout>
