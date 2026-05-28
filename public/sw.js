/**
 * K3L Monitoring - Service Worker
 * Strategi:
 *  - HTML / dokumen → network-first (selalu fresh, fallback offline page)
 *  - Static assets (CSS/JS/font/img) → cache-first (cepat, fallback network)
 *  - API/POST/PATCH/DELETE → bypass (selalu network)
 */

const VERSION = 'v3';
const STATIC_CACHE = `k3l-static-${VERSION}`;
const RUNTIME_CACHE = `k3l-runtime-${VERSION}`;

const PRECACHE = [
    '/manifest.webmanifest',
    '/images/iconpln.png',
    '/offline.html',
];

// ─────────── INSTALL ───────────
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting())
    );
});

// ─────────── ACTIVATE ───────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) =>
                Promise.all(
                    keys
                        .filter((key) => key !== STATIC_CACHE && key !== RUNTIME_CACHE)
                        .map((key) => caches.delete(key))
                )
            )
            .then(() => self.clients.claim())
    );
});

// Helper: cek apakah request HTML page navigation
function isNavigation(request) {
    return request.mode === 'navigate' ||
        (request.method === 'GET' && request.headers.get('accept')?.includes('text/html'));
}

// Helper: cek apakah request asset statis (build, gambar, font)
function isStaticAsset(url) {
    return /\.(?:js|css|woff2?|ttf|otf|eot|png|jpg|jpeg|svg|gif|webp|ico)$/i.test(url.pathname) ||
        url.pathname.startsWith('/build/') ||
        url.pathname.startsWith('/images/');
}

// ─────────── FETCH ───────────
self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // Hanya handle same-origin GET requests
    if (request.method !== 'GET') return;
    if (url.origin !== self.location.origin) return;

    // Skip Laravel auth/csrf-sensitive endpoints
    if (url.pathname.startsWith('/livewire') || url.pathname.startsWith('/_debugbar')) return;

    // HTML navigation → network-first dengan offline fallback
    if (isNavigation(request)) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response.ok && response.status === 200) {
                        const copy = response.clone();
                        caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
                    }
                    return response;
                })
                .catch(() =>
                    caches.match(request).then((cached) =>
                        cached || caches.match('/offline.html')
                    )
                )
        );
        return;
    }

    // Static assets → cache-first
    if (isStaticAsset(url)) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;
                return fetch(request).then((response) => {
                    if (response.ok) {
                        const copy = response.clone();
                        caches.open(STATIC_CACHE).then((cache) => cache.put(request, copy));
                    }
                    return response;
                });
            })
        );
        return;
    }

    // Default → network-first dengan cache fallback
    event.respondWith(
        fetch(request)
            .then((response) => {
                if (response.ok) {
                    const copy = response.clone();
                    caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
                }
                return response;
            })
            .catch(() => caches.match(request))
    );
});

// Allow page to trigger update
self.addEventListener('message', (event) => {
    if (event.data === 'SKIP_WAITING') self.skipWaiting();
});
