const NOM_CACHE = 'lessence-v2';
const RESSOURCES_STATIQUES = ['/accueil.php','/css/style-principal.css','/js/principal.js'];

self.addEventListener('install', e => {
    e.waitUntil(caches.open(NOM_CACHE).then(c => c.addAll(RESSOURCES_STATIQUES).catch(()=>{})));
    self.skipWaiting();
});
self.addEventListener('activate', e => {
    e.waitUntil(caches.keys().then(cles => Promise.all(cles.filter(c=>c!==NOM_CACHE).map(c=>caches.delete(c)))));
    self.clients.claim();
});
self.addEventListener('fetch', e => {
    if (e.request.method !== 'GET') return;
    e.respondWith(caches.match(e.request).then(r => r || fetch(e.request).then(res => {
        if (!res || res.status !== 200) return res;
        const clone = res.clone();
        caches.open(NOM_CACHE).then(c => c.put(e.request, clone));
        return res;
    }).catch(() => {})));
});
self.addEventListener('push', e => {
    if (!e.data) return;
    const d = e.data.json();
    e.waitUntil(self.registration.showNotification(d.titre || "L'ESSENCE", {body: d.corps, icon: '/images/icones/icone-192.png'}));
});
