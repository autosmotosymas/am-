<x-admin-layout title="Nuevo vendedor">
<div class="max-w-md">
    <form method="POST" action="{{ route('admin.vendedores.store') }}"
          class="bg-card border border-base rounded-2xl p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-base mb-1.5">Nombre completo *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('name') border-red-500/60 @enderror">
            @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-base mb-1.5">Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('email') border-red-500/60 @enderror">
            @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-base mb-1.5">Teléfono</label>
            <input type="tel" name="telefono" value="{{ old('telefono') }}"
                   class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        <div>
            <label class="block text-sm font-semibold text-base mb-1.5">Contraseña *</label>
            <input type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('password') border-red-500/60 @enderror">
            @error('password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-base mb-1.5">Confirmar contraseña *</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-card2 border border-base text-base rounded-xl px-3 py-2.5 focus:outline-none focus:border-brand-orange transition-colors">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Crear vendedor</button>
            <a href="{{ route('admin.vendedores.index') }}" class="btn-outline">Cancelar</a>
        </div>
    </form>
</div>
</x-admin-layout>
