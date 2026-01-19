const CACHE_NAME = 'nurul-ilmi-cache-v2';
const urlsToCache = [
    '/login',
    '/template/dist/css/adminlte.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css',
    '/template/dist/assets/img/AdminLTELogo.png'
];

self.addEventListener('install', event => {
    self.skipWaiting(); // Force new SW to active immediately
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim(); // Take control of all clients immediately
});

self.addEventListener('fetch', event => {
    // Network-first strategy for HTML implies standard navigation
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    return caches.match(event.request);
                })
        );
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
    );
});
