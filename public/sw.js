// EverGreen Cold Storage CRM service worker.
//
// Deliberately minimal: it never caches CRM pages or data (stock, bills and
// payments must always be live). It only shows a branded offline page when a
// page can't be loaded because there is no connection.
//
// Bump VERSION when offline.html or the icons change.
const VERSION = 'v2';
const CACHE = `evergreen-shell-${VERSION}`;
const OFFLINE_URL = '/offline.html';
const PRECACHE = [OFFLINE_URL, '/favicon.svg'];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE).then((cache) => cache.addAll(PRECACHE)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((k) => k.startsWith('evergreen-shell-') && k !== CACHE).map((k) => caches.delete(k))))
            .then(() => self.clients.claim()),
    );
});

self.addEventListener('fetch', (event) => {
    // Only full page loads; Livewire requests, assets and downloads go straight to the network.
    if (event.request.mode !== 'navigate') return;

    event.respondWith(
        fetch(event.request).catch(() => caches.match(OFFLINE_URL)),
    );
});
