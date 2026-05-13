<x-admin-layout title="Nueva suscripción">
<div class="max-w-md">
    <form method="POST" action="{{ route('admin.suscripciones.store') }}"
          class="bg-card border border-base rounded-2xl p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Agencia *</label>
            <select name="agencia_id" required
                    class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('agencia_id') border-red-500/60 @enderror">
                <option value="">Seleccionar agencia…</option>
                @foreach($agencias as $ag)
                    <option value="{{ $ag->id }}" {{ old('agencia_id') == $ag->id ? 'selected' : '' }}>
                        {{ $ag->nombre }} — {{ $ag->ciudad }}
                    </option>
                @endforeach
            </select>
            @error('agencia_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Plan *</label>
            <select name="plan_id" required
                    class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('plan_id') border-red-500/60 @enderror">
                <option value="">Seleccionar plan…</option>
                @foreach($planes as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->nombre }} — {{ $plan->precio_formateado }}/mes
                    </option>
                @endforeach
            </select>
            @error('plan_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-base mb-1.5">Meses *</label>
                <input type="number" name="meses" value="{{ old('meses', 1) }}" min="1" max="24" required
                       class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('meses') border-red-500/60 @enderror">
                @error('meses') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-base mb-1.5">Precio pagado ($)</label>
                <input type="number" name="precio_pagado" value="{{ old('precio_pagado') }}" min="0" step="0.01"
                       placeholder="0.00"
                       class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('precio_pagado') border-red-500/60 @enderror">
                @error('precio_pagado') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <p class="text-xs text-muted">
            Si la agencia ya tiene una suscripción activa, será cancelada automáticamente al crear la nueva.
        </p>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Activar suscripción</button>
            <a href="{{ route('admin.suscripciones.index') }}" class="btn-outline">Cancelar</a>
        </div>
    </form>
</div>
</x-admin-layout>
