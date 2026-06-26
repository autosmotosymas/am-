<x-vendedor-layout title="Nueva agencia" back="{{ route('vendedor.dashboard') }}">

<form method="POST" action="{{ route('vendedor.agencias.store') }}"
      class="px-4 pt-5 space-y-5 pb-4">
    @csrf

    {{-- Datos principales --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Datos de la agencia</p>
        <div class="space-y-3">

            <div>
                <label class="block text-xs text-gray-500 mb-1">Nombre del negocio *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                       placeholder="Autos El Güero, Lote Los Pinos…" autocomplete="off">
                @error('nombre') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                       placeholder="contacto@agencia.com">
                @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Teléfono *</label>
                    <input type="tel" name="telefono" value="{{ old('telefono') }}" required
                           class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                           placeholder="33 1234 5678">
                    @error('telefono') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">WhatsApp</label>
                    <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}"
                           class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                           placeholder="33 1234 5678">
                </div>
            </div>
        </div>
    </div>

    {{-- Ubicación --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Ubicación</p>
        <div class="space-y-3">

            <div>
                <label class="block text-xs text-gray-500 mb-1">Dirección</label>
                <input type="text" name="direccion" value="{{ old('direccion') }}"
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                       placeholder="Av. Vallarta 1234, Col. Italia" autocomplete="off">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Ciudad *</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', 'Guadalajara') }}" required
                           class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                           placeholder="Guadalajara" autocomplete="off">
                    @error('ciudad') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Estado *</label>
                    <input type="text" name="estado" value="{{ old('estado', 'Jalisco') }}" required
                           class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                           placeholder="Jalisco" autocomplete="off">
                    @error('estado') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="pt-2">
        <button type="submit"
                class="w-full bg-brand-orange hover:bg-brand-orange2 text-white font-semibold py-4 rounded-2xl text-base transition-colors active:scale-95">
            Registrar agencia
        </button>
        <a href="{{ route('vendedor.dashboard') }}"
           class="block text-center text-sm text-gray-500 mt-3 py-2">
            Cancelar
        </a>
    </div>

</form>

</x-vendedor-layout>
