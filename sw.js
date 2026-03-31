const CACHE_NAME = 'parikmaher-pwa-v1';
const urlsToCache = [
    '/',
    '/index.html',
    '/photo.jpg',
    '/slide1.jpg',
    '/slide2.jpg',
    '/slide3.jpg',
    '/slide4.jpg',
    '/apple-touch-icon.png',
    '/icon-192.png',
    '/icon-512.png'
];

// Установка Service Worker + кэширование
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Кэш открыт');
                return cache.addAll(urlsToCache);
            })
    );
});

// Стратегия кэш-first для быстрой работы
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
    );
});

// Обработка push-уведомлений
self.addEventListener('push', event => {
    const data = event.data.json();
    
    const options = {
        body: data.body || 'Новое сообщение от Анастасии Гришиной',
        icon: '/icon-192.png',
        badge: '/icon-192.png',
        vibrate: [200, 100, 200],
        data: {
            url: data.url || '/index.html'
        }
    };

    event.waitUntil(
        self.registration.showNotification(
            data.title || 'Курс парикмахера', 
            options
        )
    );
});

// Клик по уведомлению — открывает сайт
self.addEventListener('notificationclick', event => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});
