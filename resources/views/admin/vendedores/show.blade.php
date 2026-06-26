<x-admin-layout :title="$vendedor->name">

    @if(session('ok'))
        <div class="mb-4 bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-xl px-4 py-3">
            {{ session('ok') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info + edición del vendedor --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Info resumida --}}
            <div class="bg-card border border-base rounded-2xl p-5 space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-brand-orange/10 flex items-center justify-center mb-4">
                    <span class="text-brand-orange text-xl font-bold">
                        {{ strtoupper(substr($vendedor->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-muted">Registrado</p>
                    <p class="text-base">{{ $vendedor->created_at->format('d M Y') }}</p>
                </div>
                <div class="pt-3 border-t border-base">
                    <p class="text-2xl font-bold text-brand-orange">{{ $vendedor->agenciasVendidas->count() }}</p>
                    <p class="text-xs text-muted">{{ $vendedor->agenciasVendidas->count() === 1 ? 'agencia registrada' : 'agencias registradas' }}</p>
                </div>
            </div>

            {{-- Formulario de edición --}}
            <div class="bg-card border border-base rounded-2xl p-5">
                <h2 class="font-semibold text-base mb-4">Editar vendedor</h2>

                <form method="POST" action="{{ route('admin.vendedores.update', $vendedor) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-muted mb-1">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $vendedor->name) }}" required
                               class="input-amm @error('name') border-red-500/60 @enderror">
                        @error('name')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-muted mb-1">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $vendedor->email) }}" required
                               class="input-amm @error('email') border-red-500/60 @enderror">
                        @error('email')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-muted mb-1">Teléfono</label>
                        <input type="tel" name="telefono" value="{{ old('telefono', $vendedor->telefono) }}"
                               class="input-amm" placeholder="33 1234 5678">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-muted mb-1">Nueva contraseña <span class="font-normal text-muted">(dejar vacío para no cambiar)</span></label>
                        <input type="password" name="password"
                               class="input-amm @error('password') border-red-500/60 @enderror"
                               placeholder="Mínimo 8 caracteres">
                        @error('password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-muted mb-1">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation"
                               class="input-amm" placeholder="Repetir contraseña">
                    </div>

                    <button type="submit" class="btn-primary w-full justify-center">
                        Guardar cambios
                    </button>
                </form>
            </div>
        </div>

        {{-- Agencias del vendedor --}}
        <div class="lg:col-span-2">
            <h2 class="text-base font-semibold text-base mb-3">Agencias registradas</h2>

            @if($vendedor->agenciasVendidas->isEmpty())
                <div class="bg-card border border-base rounded-2xl p-8 text-center text-muted text-sm">
                    Este vendedor no ha registrado agencias aún.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($vendedor->agenciasVendidas as $ag)
                        <div class="bg-card border border-base rounded-2xl p-4 flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-base">{{ $ag->nombre }}</p>
                                    <span class="text-xs px-2 py-0.5 rounded-full border
                                        {{ $ag->activo ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' }}">
                                        {{ $ag->activo ? 'Activa' : 'Pendiente' }}
                                    </span>
                                </div>
                                <p class="text-xs text-muted mt-0.5">{{ $ag->ciudad }}, {{ $ag->estado }}</p>
                                <p class="text-xs text-muted">{{ $ag->vehiculos->count() }} {{ $ag->vehiculos->count() === 1 ? 'vehículo' : 'vehículos' }}</p>
                            </div>
                            <a href="{{ route('admin.agencias.show', $ag) }}"
                               class="text-xs text-brand-orange hover:underline shrink-0">Ver agencia</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-admin-layout>
