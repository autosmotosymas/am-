<x-app-layout title="Mi perfil — AutosMotosYMás">

<div class="bg-page min-h-screen py-10">
    <div class="container-amm max-w-3xl">

        {{-- Encabezado --}}
        <div class="flex items-center gap-4 mb-8">
            <img src="{{ $user->avatar_url }}"
                 alt="{{ $user->name }}"
                 class="w-16 h-16 rounded-2xl object-cover border-2 border-brand-orange/30">
            <div>
                <h1 class="text-xl font-bold text-base">{{ $user->name }}</h1>
                <p class="text-sm text-muted">{{ $user->email }}</p>
                <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-brand-orange/10 text-brand-orange border border-brand-orange/20 capitalize">
                    {{ $user->getRoleNames()->first() ?? 'comprador' }}
                </span>
            </div>
        </div>

        @if(session('guardado'))
            <div class="mb-6 bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-xl px-4 py-3">
                Perfil actualizado correctamente.
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-[1fr_280px] gap-6 items-start">

            {{-- ── FORMULARIO ── --}}
            <div class="space-y-5">

                {{-- Datos personales --}}
                <div class="bg-card border border-base rounded-2xl p-6">
                    <h2 class="font-semibold text-base mb-5">Datos personales</h2>

                    <form method="POST" action="{{ route('perfil.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-base mb-1.5">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('name') border-red-500/60 @enderror">
                            @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-base mb-1.5">Teléfono</label>
                            <input type="tel" name="telefono" value="{{ old('telefono', $user->telefono) }}"
                                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors"
                                   placeholder="33 1234 5678">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-base mb-1.5">Correo electrónico</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('email') border-red-500/60 @enderror">
                            @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="btn-primary">
                            Guardar cambios
                        </button>
                    </form>
                </div>

                {{-- Cambiar contraseña --}}
                <div class="bg-card border border-base rounded-2xl p-6" x-data="{ abierto: false }">
                    <button @click="abierto = !abierto"
                            class="flex items-center justify-between w-full text-left">
                        <h2 class="font-semibold text-base">Cambiar contraseña</h2>
                        <svg class="w-4 h-4 text-muted transition-transform" :class="abierto ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <form x-show="abierto" x-transition method="POST" action="{{ route('perfil.update') }}"
                          class="space-y-4 mt-5">
                        @csrf
                        @method('PUT')
                        {{-- Campos sin nombre/email para que no sobreescriba --}}
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">

                        <div>
                            <label class="block text-xs font-semibold text-base mb-1.5">Contraseña actual</label>
                            <input type="password" name="password_actual" required
                                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('password_actual') border-red-500/60 @enderror"
                                   placeholder="••••••••">
                            @error('password_actual') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-base mb-1.5">Nueva contraseña</label>
                            <input type="password" name="password" required
                                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors @error('password') border-red-500/60 @enderror"
                                   placeholder="Mínimo 8 caracteres">
                            @error('password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-base mb-1.5">Confirmar nueva contraseña</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full bg-card2 border border-base text-base text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-brand-orange transition-colors"
                                   placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn-primary">Actualizar contraseña</button>
                    </form>
                </div>
            </div>

            {{-- ── SIDEBAR ── --}}
            <div class="space-y-4">

                {{-- Tema --}}
                <div class="bg-card border border-base rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-base mb-3">Tema de la app</h3>
                    <div class="flex gap-2">
                        @foreach(['dark' => 'Oscuro', 'light' => 'Claro'] as $val => $label)
                            <button
                                onclick="
                                    const isDark = '{{ $val }}' === 'dark';
                                    document.documentElement.classList.toggle('dark', isDark);
                                    localStorage.setItem('amm_tema', '{{ $val }}');
                                    fetch('{{ route('perfil.tema') }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                                        body: JSON.stringify({ tema: '{{ $val }}' })
                                    });
                                "
                                class="flex-1 py-2 text-xs font-medium rounded-lg border transition-colors
                                       {{ $user->tema === $val
                                           ? 'bg-brand-orange text-white border-brand-orange'
                                           : 'bg-card2 text-muted border-base hover:border-brand-orange/40' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Stats --}}
                <div class="bg-card border border-base rounded-2xl p-5">
                    <h3 class="text-sm font-semibold text-base mb-3">Mi actividad</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-muted">Mensajes enviados</span>
                            <span class="font-semibold text-base">{{ $user->leads->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-muted">Miembro desde</span>
                            <span class="font-semibold text-base">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Cerrar sesión --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-outline w-full justify-center text-red-400 border-red-500/20 hover:border-red-500/40 hover:bg-red-500/5">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        {{-- Mensajes recientes --}}
        @if($user->leads->isNotEmpty())
            <div class="mt-8">
                <h2 class="font-semibold text-base mb-4">Mis mensajes enviados</h2>
                <div class="space-y-3">
                    @foreach($user->leads->take(10) as $lead)
                        <div class="bg-card border border-base rounded-xl p-4 flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                @if($lead->vehiculo)
                                    <a href="{{ route('vehiculo.show', $lead->vehiculo) }}"
                                       class="text-sm font-medium text-base hover:text-brand-orange transition-colors truncate block">
                                        {{ $lead->vehiculo->anio }} {{ $lead->vehiculo->marca }} {{ $lead->vehiculo->modelo }}
                                    </a>
                                @endif
                                <p class="text-xs text-muted mt-0.5">
                                    {{ $lead->created_at->diffForHumans() }} · vía {{ ucfirst($lead->tipo) }}
                                </p>
                            </div>
                            <span class="shrink-0 text-xs px-2 py-1 rounded-full border
                                @if($lead->status === 'nuevo') bg-blue-500/10 text-blue-400 border-blue-500/20
                                @elseif($lead->status === 'en_proceso') bg-yellow-500/10 text-yellow-400 border-yellow-500/20
                                @elseif($lead->status === 'cerrado_ganado') bg-green-500/10 text-green-400 border-green-500/20
                                @else bg-gray-500/10 text-gray-400 border-gray-500/20
                                @endif">
                                {{ ['nuevo' => 'Nuevo', 'en_proceso' => 'En proceso', 'cerrado_ganado' => 'Comprado', 'cerrado_perdido' => 'Cerrado'][$lead->status] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

</x-app-layout>
