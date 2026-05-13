import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ─── Service Worker (PWA Captura) ─────────────────────────────────────────────
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker
            .register('/pwa/sw.js', { scope: '/captura' })
            .then((reg) => {
                // Verificar actualizaciones cada visita
                reg.update();
            })
            .catch((err) => {
                console.warn('[SW] Registro fallido:', err);
            });
    });
}
