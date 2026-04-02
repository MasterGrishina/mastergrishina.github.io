// sw.js — минимальная версия для push-уведомлений

const CACHE_NAME = 'parikmaher-pwa-v2';

self.addEventListener('install', event => {
    console.log('Service Worker установлен');
    self.skipWaiting(); // сразу активировать
});

self.addEventListener('activate', event => {
    console.log('Service Worker активирован');
    event.waitUntil(self.clients.claim());
});

// Обработка push-уведомлений
self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : { title: 'Уведомление', body: 'Новое сообщение' };

    const options = {
        body: data.body,
        icon: '/web-app-manifest-192x192.png',
        badge: '/web-app-manifest-192x192.png',
        vibrate: [200, 100, 200]
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'Курс парикмахера', options)
    );
});

self.addEventListener('notificationclick', event => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow('/')
    );
});
