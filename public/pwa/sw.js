// AMM — Service Worker
// Estrategia: Cache-first para assets estáticos, Network-first para páginas HTML

const CACHE_NAME    = 'amm-captura-v1';
const STATIC_ASSETS = [
    '/captura',
    '/img/logo_amm.png',
    '/img/icons/icon-192.png',
    '/img/icons/icon-512.png',
];

// ─── Install ──────────────────────────────────────────────────────────────────
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

// ─── Activate ─────────────────────────────────────────────────────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

// ─── Fetch ────────────────────────────────────────────────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Solo interceptar mismo origen
    if (url.origin !== location.origin) return;

    // Assets estáticos (build Vite, imágenes, fuentes) → Cache-first
    if (isStaticAsset(url.pathname)) {
        event.respondWith(cacheFirst(request));
        return;
    }

    // Páginas de captura → Network-first con fallback a cache
    if (url.pathname.startsWith('/captura')) {
        event.respondWith(networkFirst(request));
        return;
    }
});

// ─── Estrategias ──────────────────────────────────────────────────────────────

function isStaticAsset(pathname) {
    return (
        pathname.startsWith('/build/') ||
        pathname.startsWith('/img/')   ||
        pathname.startsWith('/fonts/') ||
        /\.(css|js|woff2?|ttf|png|jpg|jpeg|svg|ico|webp)$/.test(pathname)
    );
}

async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        return new Response('Recurso no disponible sin conexión.', { status: 503 });
    }
}

async function networkFirst(request) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        const cached = await caches.match(request);
        if (cached) return cached;

        // Fallback offline para páginas de captura
        return new Response(offlinePage(), {
            status: 200,
            headers: { 'Content-Type': 'text/html; charset=utf-8' },
        });
    }
}

function offlinePage() {
    return `<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#111111">
  <title>Sin conexión — AMM Captura</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: system-ui, sans-serif;
      background: #111;
      color: #fff;
      min-height: 100dvh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 1.5rem;
      padding: 2rem;
      text-align: center;
    }
    .icon { font-size: 4rem; }
    h1 { font-size: 1.25rem; font-weight: 700; }
    p  { font-size: .875rem; color: #888; max-width: 280px; }
    button {
      margin-top: .5rem;
      background: #E8710A;
      color: #fff;
      border: none;
      border-radius: 1rem;
      padding: .75rem 2rem;
      font-size: .875rem;
      font-weight: 600;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <span class="icon">📡</span>
  <h1>Sin conexión</h1>
  <p>Revisa tu red e intenta de nuevo. Los datos capturados se guardan cuando recuperes señal.</p>
  <button onclick="location.reload()">Reintentar</button>
</body>
</html>`;
}

// ─── Background Sync (envío de fotos pendientes) ──────────────────────────────
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-capturas') {
        event.waitUntil(syncPendingCapturas());
    }
});

async function syncPendingCapturas() {
    // Placeholder para cuando implementemos IndexedDB queue
    console.log('[SW] Background sync: capturas pendientes');
}
