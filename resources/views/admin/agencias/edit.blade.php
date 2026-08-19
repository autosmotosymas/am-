<x-admin-layout :title="'Editar: ' . $agencia->nombre">
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.agencias.update', $agencia) }}"
          class="bg-card border border-base rounded-2xl p-6 space-y-4">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-base mb-1.5">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $agencia->nombre) }}" required
                       class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('nombre') border-red-500/60 @enderror">
                @error('nombre') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-base mb-1.5">Email *</label>
                <input type="email" name="email" value="{{ old('email', $agencia->email) }}" required
                       class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('email') border-red-500/60 @enderror">
                @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-base mb-1.5">Teléfono *</label>
                <input type="text" name="telefono" value="{{ old('telefono', $agencia->telefono) }}" required
                       class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('telefono') border-red-500/60 @enderror">
                @error('telefono') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-base mb-1.5">Ciudad *</label>
                <input type="text" name="ciudad" value="{{ old('ciudad', $agencia->ciudad) }}" required
                       class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-base mb-1.5">Estado *</label>
                <input type="text" name="estado" value="{{ old('estado', $agencia->estado) }}" required
                       class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-base mb-1.5">WhatsApp</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $agencia->whatsapp) }}"
                       class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-base mb-1.5">Dirección</label>
                <input type="text" name="direccion" value="{{ old('direccion', $agencia->direccion) }}"
                       class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Guardar cambios</button>
            <a href="{{ route('admin.agencias.show', $agencia) }}" class="btn-outline">Cancelar</a>
        </div>
    </form>
</div>
</x-admin-layout>
