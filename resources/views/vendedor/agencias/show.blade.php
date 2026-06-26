<x-vendedor-layout :title="$agencia->nombre" back="{{ route('vendedor.dashboard') }}">

<div class="px-4 pt-5 space-y-6 pb-4">

    {{-- Status banner --}}
    @if($agencia->activo)
        <div class="bg-green-500/15 border border-green-500/30 rounded-2xl px-4 py-3 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-green-400">Agencia activa</p>
                <p class="text-xs text-green-400/70">Ya aparece en el sitio y sus autos son visibles.</p>
            </div>
        </div>
    @else
        <div class="bg-yellow-500/15 border border-yellow-500/30 rounded-2xl px-4 py-3 flex items-center gap-3">
            <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-yellow-400">Pendiente de activación</p>
                <p class="text-xs text-yellow-400/70">Elige un plan para que la agencia aparezca en el sitio.</p>
            </div>
        </div>
    @endif

    {{-- Stats rápidas --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white/5 rounded-2xl p-4 border border-white/8">
            <p class="text-2xl font-bold text-white">{{ $vehiculos }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $vehiculos === 1 ? 'vehículo' : 'vehículos' }}</p>
        </div>
        <a href="{{ route('vendedor.vehiculos.index', $agencia) }}"
           class="bg-brand-orange/10 border border-brand-orange/20 rounded-2xl p-4 flex items-center justify-between active:scale-95 transition-all">
            <div>
                <p class="text-sm font-semibold text-brand-orange">Ver inventario</p>
                <p class="text-xs text-brand-orange/60">Capturar más</p>
            </div>
            <svg class="w-5 h-5 text-brand-orange" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </a>
    </div>

    {{-- Planes (solo si no tiene suscripción activa) --}}
    @if(!$agencia->activo || !$suscripcion)
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Elige un plan</p>
            <div class="space-y-3">
                @foreach($planes as $plan)
                    <div class="bg-white/5 border {{ $plan->badge_premium ? 'border-brand-orange/40' : 'border-white/8' }} rounded-2xl p-4">
                        @if($plan->badge_premium)
                            <span class="text-[10px] font-bold text-brand-orange bg-brand-orange/10 px-2 py-0.5 rounded-full border border-brand-orange/20 mb-2 inline-block">RECOMENDADO</span>
                        @endif
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <p class="font-bold text-white">{{ $plan->nombre }}</p>
                                <p class="text-2xl font-bold text-brand-orange mt-0.5">
                                    ${{ number_format($plan->precio_mensual, 0) }}
                                    <span class="text-sm font-normal text-gray-500">/mes</span>
                                </p>
                            </div>
                        </div>
                        <ul class="space-y-1.5 mb-4">
                            <li class="flex items-center gap-2 text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                Hasta {{ $plan->max_vehiculos }} vehículos activos
                            </li>
                            <li class="flex items-center gap-2 text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                {{ $plan->max_fotos_por_vehiculo }} fotos por vehículo
                            </li>
                            @if($plan->vehiculos_destacados > 0)
                            <li class="flex items-center gap-2 text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                {{ $plan->vehiculos_destacados }} vehículos destacados
                            </li>
                            @endif
                            @if($plan->incluye_certificacion)
                            <li class="flex items-center gap-2 text-xs text-gray-400">
                                <svg class="w-3.5 h-3.5 text-green-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                                3 certificaciones incluidas/mes
                            </li>
                            @endif
                        </ul>
                        <form method="POST" action="{{ route('vendedor.agencias.checkout', $agencia) }}">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit"
                                    class="w-full {{ $plan->badge_premium ? 'bg-brand-orange hover:bg-brand-orange2' : 'bg-white/10 hover:bg-white/15' }} text-white font-semibold py-3 rounded-xl text-sm transition-colors active:scale-95">
                                Contratar {{ $plan->nombre }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        {{-- Suscripción activa --}}
        <div class="bg-white/5 border border-white/8 rounded-2xl p-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Suscripción</p>
            <p class="font-bold text-white">{{ $suscripcion->plan->nombre }}</p>
            <p class="text-xs text-gray-500 mt-1">Vigente hasta {{ $suscripcion->fecha_vencimiento->format('d M Y') }}</p>
        </div>
    @endif

    {{-- Editar perfil --}}
    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Perfil de la agencia</p>
        <form method="POST" action="{{ route('vendedor.agencias.update', $agencia) }}"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf @method('PUT')

            {{-- Logo --}}
            <div>
                <label class="block text-xs text-gray-500 mb-2">Logo</label>
                <div class="flex items-center gap-3">
                    @if($agencia->logo)
                        <img src="{{ asset('storage/' . $agencia->logo) }}"
                             alt="Logo" class="w-14 h-14 rounded-xl object-cover bg-white/10">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                        </div>
                    @endif
                    <label class="flex-1 flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 cursor-pointer hover:bg-white/8 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                        </svg>
                        <span class="text-xs text-gray-400">{{ $agencia->logo ? 'Cambiar logo' : 'Subir logo' }}</span>
                        <input type="file" name="logo" accept="image/*" class="hidden">
                    </label>
                </div>
            </div>

            {{-- Banner --}}
            <div>
                <label class="block text-xs text-gray-500 mb-2">Banner / foto del local</label>
                @if($agencia->banner)
                    <img src="{{ asset('storage/' . $agencia->banner) }}"
                         alt="Banner" class="w-full h-24 object-cover rounded-xl mb-2">
                @endif
                <label class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 cursor-pointer hover:bg-white/8 transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                    <span class="text-xs text-gray-400">{{ $agencia->banner ? 'Cambiar banner' : 'Subir banner / foto del local' }}</span>
                    <input type="file" name="banner" accept="image/*" class="hidden">
                </label>
            </div>

            {{-- WhatsApp --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">WhatsApp</label>
                <input type="tel" name="whatsapp" value="{{ old('whatsapp', $agencia->whatsapp) }}"
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                       placeholder="33 1234 5678">
            </div>

            {{-- Dirección --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Dirección</label>
                <input type="text" name="direccion" value="{{ old('direccion', $agencia->direccion) }}"
                       class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors placeholder-gray-600"
                       placeholder="Av. Vallarta 1234, Col. Italia">
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                          class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-xl px-3 py-3 focus:outline-none focus:border-brand-orange transition-colors resize-none placeholder-gray-600"
                          placeholder="Años de experiencia, especialidad, servicios que ofrecen…">{{ old('descripcion', $agencia->descripcion) }}</textarea>
            </div>

            <button type="submit"
                    class="w-full bg-white/10 hover:bg-white/15 text-white font-semibold py-3 rounded-2xl text-sm transition-colors active:scale-95">
                Guardar perfil
            </button>
        </form>
    </div>

</div>

</x-vendedor-layout>
