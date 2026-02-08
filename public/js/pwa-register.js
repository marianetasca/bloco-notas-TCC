// Registrar Service Worker
console.log('pwa-register.js: init - location:', window.location.href);
console.log('pwa-register.js: navigator.serviceWorker.controller ->', !!navigator.serviceWorker.controller);

// Quick manifest reachability check
fetch('/manifest.json').then(res => {
    console.log('pwa-register.js: manifest fetch ok?', res.ok, 'status', res.status);
}).catch(err => {
    console.error('pwa-register.js: manifest fetch error', err);
});

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
            .then((registration) => {
                console.log('✅ Service Worker registrado com sucesso:', registration.scope);
                console.log('pwa-register.js: registration.active ->', !!registration.active);

                // Verificar atualizações
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    console.log('🔄 Nova versão do Service Worker encontrada');

                    newWorker.addEventListener('statechange', () => {
                        console.log('pwa-register.js: newWorker state ->', newWorker.state);
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // Nova versão disponível
                            if (confirm('Nova versão disponível! Deseja atualizar?')) {
                                newWorker.postMessage({ type: 'SKIP_WAITING' });
                                window.location.reload();
                            }
                        }
                    });
                });
            })
            .catch((error) => {
                console.log('❌ Falha ao registrar Service Worker:', error);
            });

        // Atualizar quando um novo Service Worker assumir o controle
        let refreshing = false;
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            console.log('pwa-register.js: controllerchange event');
            if (!refreshing) {
                refreshing = true;
                window.location.reload();
            }
        });

        // Debug: check deferredPrompt after load
        window.addEventListener('DOMContentLoaded', () => {
            console.log('pwa-register.js: DOMContentLoaded - deferredPrompt ->', !!window.deferredPrompt);
        });

        setTimeout(() => {
            console.log('pwa-register.js: deferredPrompt after 3s ->', !!window.deferredPrompt);
        }, 3000);
    });
}

// Detectar quando o app é instalado
window.addEventListener('beforeinstallprompt', (e) => {
    console.log('💡 PWA pode ser instalado');
    // Prevenir o mini-infobar automático do Chrome
    e.preventDefault();
    // Salvar o evento para poder dispará-lo depois
    window.deferredPrompt = e;

    // Opcional: Mostrar botão de instalação customizado
    const installButton = document.getElementById('install-button');
    if (installButton) {
        installButton.style.display = 'block';

        installButton.addEventListener('click', async () => {
            if (window.deferredPrompt) {
                window.deferredPrompt.prompt();
                const { outcome } = await window.deferredPrompt.userChoice;
                console.log(`User response: ${outcome}`);
                window.deferredPrompt = null;
                installButton.style.display = 'none';
            }
        });
    }
});

// Detectar quando o app foi instalado
window.addEventListener('appinstalled', () => {
    console.log('🎉 PWA foi instalado com sucesso');
    window.deferredPrompt = null;
});

// Verificar se está rodando como PWA instalado
if (window.matchMedia('(display-mode: standalone)').matches) {
    console.log('📱 Rodando como PWA instalado');
}
