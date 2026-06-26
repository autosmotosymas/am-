<x-agencia-layout :title="'Fotos — ' . $vehiculo->anio . ' ' . $vehiculo->marca . ' ' . $vehiculo->modelo">

    @push('head')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    @endpush

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('agencia.vehiculos.edit', $vehiculo) }}"
           class="btn-ghost w-9 h-9 p-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-base">Fotos del vehículo</h1>
            <p class="text-sm text-muted">{{ $vehiculo->anio }} {{ $vehiculo->marca }} {{ $vehiculo->modelo }}</p>
        </div>
    </div>

    <div class="bg-card border border-base rounded-2xl p-5">

        <p class="text-sm text-muted mb-4">
            <span id="foto-count">{{ $fotos->count() }}</span> foto(s) · Arrastra para reordenar
        </p>

        {{-- Grid de fotos --}}
        <div id="sortable-fotos" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3 mb-5">
            @foreach($fotos as $foto)
                <div class="foto-item relative aspect-square rounded-xl overflow-hidden bg-card2 cursor-grab active:cursor-grabbing border border-base"
                     data-id="{{ $foto->id }}">

                    <img src="{{ $foto->url }}" alt="" class="w-full h-full object-cover select-none pointer-events-none">

                    @if($foto->es_principal)
                        <span class="principal-badge absolute top-1 left-1 text-[9px] bg-brand-orange text-white px-1.5 py-0.5 rounded-full font-semibold">
                            Principal
                        </span>
                    @endif

                    <div class="absolute bottom-0 inset-x-0 flex gap-0.5 p-1 bg-gradient-to-t from-black/70">
                        <button type="button"
                                onclick="setPrincipal({{ $foto->id }}, this)"
                                class="flex-1 text-[9px] bg-white/20 hover:bg-brand-orange/80 text-white rounded-lg py-1 transition-colors"
                                title="Establecer como principal">★</button>
                        <button type="button"
                                onclick="eliminarFoto({{ $foto->id }}, this)"
                                class="flex-1 text-[9px] bg-white/20 hover:bg-red-500/80 text-white rounded-lg py-1 transition-colors"
                                title="Eliminar">✕</button>
                    </div>
                </div>
            @endforeach
        </div>

        @if($fotos->isEmpty())
            <p class="text-center text-muted text-sm py-6" id="empty-state">Sin fotos aún.</p>
        @endif

        {{-- Agregar fotos --}}
        <label class="flex flex-col items-center justify-center gap-2 border border-dashed border-base rounded-xl py-6 cursor-pointer hover:border-brand-orange/50 transition-colors">
            <svg class="w-7 h-7 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z"/>
            </svg>
            <span class="text-sm text-muted">Agregar fotos</span>
            <input type="file" accept="image/*" multiple class="hidden" id="input-fotos" onchange="agregarFotos(this.files)">
        </label>

        <div id="upload-progress" class="hidden mt-3 space-y-1"></div>
    </div>

    @push('scripts')
    <script>
    const CSRF     = document.querySelector('meta[name="csrf-token"]').content;
    const BASE_URL = `/agencia/vehiculos/{{ $vehiculo->slug }}/fotos`;

    document.addEventListener('DOMContentLoaded', () => {
        const grid = document.getElementById('sortable-fotos');
        if (!grid) return;

        Sortable.create(grid, {
            animation: 150,
            ghostClass: 'opacity-40',
            onEnd() {
                const orden = [...grid.querySelectorAll('.foto-item')]
                              .map(el => parseInt(el.dataset.id));

                fetch(`${BASE_URL}/reordenar`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ orden }),
                }).catch(console.error);
            }
        });
    });

    function setPrincipal(fotoId, btn) {
        fetch(`${BASE_URL}/${fotoId}/principal`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (!data.ok) return;
            document.querySelectorAll('.principal-badge').forEach(el => el.remove());
            const badge = document.createElement('span');
            badge.className = 'principal-badge absolute top-1 left-1 text-[9px] bg-brand-orange text-white px-1.5 py-0.5 rounded-full font-semibold';
            badge.textContent = 'Principal';
            btn.closest('.foto-item').appendChild(badge);
        })
        .catch(console.error);
    }

    function eliminarFoto(fotoId, btn) {
        if (!confirm('¿Eliminar esta foto?')) return;

        fetch(`${BASE_URL}/${fotoId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (!data.ok) return;
            btn.closest('.foto-item').remove();
            actualizarContador();
        })
        .catch(console.error);
    }

    async function agregarFotos(files) {
        const progress = document.getElementById('upload-progress');
        progress.classList.remove('hidden');
        progress.innerHTML = '';

        for (const file of files) {
            const row = document.createElement('div');
            row.className = 'text-xs text-muted flex items-center gap-2';
            row.innerHTML = `<span class="truncate flex-1">${file.name}</span><span class="status">Subiendo…</span>`;
            progress.appendChild(row);

            const fd = new FormData();
            fd.append('foto', file);

            try {
                const res  = await fetch(BASE_URL, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: fd,
                });
                const data = await res.json();

                if (data.ok) {
                    row.querySelector('.status').textContent = '✓';
                    row.querySelector('.status').classList.add('text-green-400');
                    insertarFotoEnGrid(data.id, data.url);
                } else {
                    row.querySelector('.status').textContent = 'Error';
                    row.querySelector('.status').classList.add('text-red-400');
                }
            } catch {
                row.querySelector('.status').textContent = 'Error';
                row.querySelector('.status').classList.add('text-red-400');
            }
        }

        document.getElementById('input-fotos').value = '';
        setTimeout(() => { progress.classList.add('hidden'); progress.innerHTML = ''; }, 3000);
    }

    function insertarFotoEnGrid(id, url) {
        const grid = document.getElementById('sortable-fotos');
        const empty = document.getElementById('empty-state');
        if (empty) empty.remove();

        const div = document.createElement('div');
        div.className = 'foto-item relative aspect-square rounded-xl overflow-hidden bg-card2 cursor-grab active:cursor-grabbing border border-base';
        div.dataset.id = id;
        div.innerHTML = `
            <img src="${url}" alt="" class="w-full h-full object-cover select-none pointer-events-none">
            <div class="absolute bottom-0 inset-x-0 flex gap-0.5 p-1 bg-gradient-to-t from-black/70">
                <button type="button" onclick="setPrincipal(${id}, this)"
                        class="flex-1 text-[9px] bg-white/20 hover:bg-brand-orange/80 text-white rounded-lg py-1 transition-colors">★</button>
                <button type="button" onclick="eliminarFoto(${id}, this)"
                        class="flex-1 text-[9px] bg-white/20 hover:bg-red-500/80 text-white rounded-lg py-1 transition-colors">✕</button>
            </div>`;

        if (grid.querySelectorAll('.foto-item').length === 0) {
            const badge = document.createElement('span');
            badge.className = 'principal-badge absolute top-1 left-1 text-[9px] bg-brand-orange text-white px-1.5 py-0.5 rounded-full font-semibold';
            badge.textContent = 'Principal';
            div.appendChild(badge);
        }

        grid.appendChild(div);
        actualizarContador();
    }

    function actualizarContador() {
        const count = document.querySelectorAll('.foto-item').length;
        const el = document.getElementById('foto-count');
        if (el) el.textContent = count;
    }
    </script>
    @endpush

</x-agencia-layout>
