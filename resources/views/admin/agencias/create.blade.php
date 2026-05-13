<x-admin-layout title="Nueva agencia">
<div class="max-w-xl">
    <form method="POST" action="{{ route('admin.agencias.store') }}" class="bg-card border border-base rounded-2xl p-6 space-y-4">
        @csrf
        @foreach(['nombre' => 'Nombre *', 'email' => 'Email *', 'telefono' => 'Teléfono *', 'whatsapp' => 'WhatsApp', 'ciudad' => 'Ciudad *', 'estado' => 'Estado *', 'direccion' => 'Dirección'] as $campo => $label)
            <div>
                <label class="block text-xs font-semibold text-base mb-1.5">{{ $label }}</label>
                <input type="{{ $campo === 'email' ? 'email' : 'text' }}"
                       name="{{ $campo }}" value="{{ old($campo) }}"
                       {{ str_ends_with($label, '*') ? 'required' : '' }}
                       class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error($campo) border-red-500/60 @enderror">
                @error($campo) <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
        @endforeach
        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Crear agencia</button>
            <a href="{{ route('admin.agencias.index') }}" class="btn-outline">Cancelar</a>
        </div>
    </form>
</div>
</x-admin-layout>
