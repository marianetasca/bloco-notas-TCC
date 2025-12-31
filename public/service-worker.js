const CACHE_NAME = 'mindly-notes-v1';
const urlsToCache = [
  '/',
  '/offline.html'
];

// Instalação do Service Worker
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Instalando...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[Service Worker] Cache aberto');
        // Cachear apenas URLs que sabemos que existem
        return cache.addAll(urlsToCache).catch((error) => {
          console.warn('[Service Worker] Erro ao cachear algumas URLs:', error);
          // Continua mesmo se algumas URLs falharem
        });
      })
  );
  self.skipWaiting();
});

// Ativação do Service Worker
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Ativando...');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('[Service Worker] Removendo cache antigo:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  return self.clients.claim();
});

// Interceptar requisições
self.addEventListener('fetch', (event) => {
  const { request } = event;

  // Ignorar requisições que não são GET
  if (request.method !== 'GET') {
    return;
  }

  // Ignorar requisições de API, storage, etc
  const url = new URL(request.url);
  if (
    url.pathname.startsWith('/storage/') ||
    url.pathname.startsWith('/api/') ||
    url.pathname.includes('livewire') ||
    url.pathname.includes('_debugbar')
  ) {
    return;
  }

  event.respondWith(
    fetch(request)
      .then((response) => {
        // Só cachear respostas válidas
        if (!response || response.status !== 200 || response.type !== 'basic') {
          return response;
        }

        // Clonar a resposta
        const responseToCache = response.clone();

        // Cachear apenas certos tipos de arquivos
        if (
          url.pathname.endsWith('.js') ||
          url.pathname.endsWith('.css') ||
          url.pathname.endsWith('.png') ||
          url.pathname.endsWith('.jpg') ||
          url.pathname.endsWith('.jpeg') ||
          url.pathname.endsWith('.svg') ||
          url.pathname.endsWith('.woff') ||
          url.pathname.endsWith('.woff2')
        ) {
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, responseToCache);
          });
        }

        return response;
      })
      .catch(() => {
        // Se falhar, tenta buscar do cache
        return caches.match(request).then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse;
          }

          // Se for navegação e não tem cache, mostra página offline
          if (request.mode === 'navigate') {
            return caches.match('/offline.html');
          }
        });
      })
  );
});

// Lidar com mensagens
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
