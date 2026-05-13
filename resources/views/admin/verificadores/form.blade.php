@php $editando = isset($verificador); @endphp
<x-admin-layout :title="$editando ? 'Editar verificador' : 'Nuevo verificador'">
<div class="max-w-md">
    <form method="POST"
          action="{{ $editando ? route('admin.verificadores.update', $verificador) : route('admin.verificadores.store') }}"
          class="bg-card border border-base rounded-2xl p-6 space-y-4">
        @csrf
        @if($editando) @method('PUT') @endif

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Nombre *</label>
            <input type="text" name="nombre" value="{{ old('nombre', $verificador->nombre ?? '') }}" required
                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('nombre') border-red-500/60 @enderror">
            @error('nombre') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Email *</label>
            <input type="email" name="email" value="{{ old('email', $verificador->email ?? '') }}" required
                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('email') border-red-500/60 @enderror">
            @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Teléfono</label>
            <input type="tel" name="telefono" value="{{ old('telefono', $verificador->telefono ?? '') }}"
                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        <div>
            <label class="block text-xs font-semibold text-base mb-1.5">Zona de operación</label>
            <input type="text" name="zona" value="{{ old('zona', $verificador->zona ?? '') }}"
                   placeholder="Ej. Zapopan, Tlaquepaque"
                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        @if($editando)
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="activo" value="1"
                       {{ old('activo', $verificador->activo) ? 'checked' : '' }}
                       class="w-4 h-4 accent-brand-orange">
                <span class="text-sm text-base">Activo</span>
            </label>
        @endif

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">
                {{ $editando ? 'Guardar cambios' : 'Registrar verificador' }}
            </button>
            <a href="{{ route('admin.verificadores.index') }}" class="btn-outline">Cancelar</a>
        </div>
    </form>
</div>
</x-admin-layout>
