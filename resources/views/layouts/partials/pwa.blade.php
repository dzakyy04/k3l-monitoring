{{--
    PWA service worker manager + update banner.
    Install button is in topbar — only shows when native prompt is available.
    window.pwa is set up early in head.
--}}

{{-- Update available banner --}}
<div
    x-data="pwaUI()"
    x-cloak
    class="fixed bottom-[calc(5.5rem+env(safe-area-inset-bottom))] lg:bottom-5 left-1/2 -translate-x-1/2 z-[60] w-[calc(100%-1.5rem)] max-w-md pointer-events-none"
>
    <div
        x-show="showUpdate"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="pointer-events-auto bg-slate-900 dark:bg-slate-700 text-white rounded-2xl shadow-pop p-4 flex items-center gap-3"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
        <p class="flex-1 text-sm font-semibold truncate">Versi baru tersedia</p>
        <button type="button" x-on:click="applyUpdate()"
            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-slate-900 bg-white hover:bg-slate-100 active:bg-slate-200 rounded-full cursor-pointer focus-ring transition-colors shrink-0">
            Reload
        </button>
    </div>
</div>

<script>
    // Service worker registration with update detection
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js').then(function(reg) {
                reg.addEventListener('updatefound', function() {
                    var newWorker = reg.installing;
                    if (!newWorker) return;
                    newWorker.addEventListener('statechange', function() {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            window.dispatchEvent(new CustomEvent('pwa-update-available', { detail: { worker: newWorker } }));
                        }
                    });
                });
                setInterval(function() { reg.update(); }, 30 * 60 * 1000);
            });

            var refreshing = false;
            navigator.serviceWorker.addEventListener('controllerchange', function() {
                if (refreshing) return;
                refreshing = true;
                window.location.reload();
            });
        });
    }

    function pwaUI() {
        return {
            showUpdate: false,
            updateWorker: null,
            init: function() {
                var self = this;
                window.addEventListener('pwa-update-available', function(e) {
                    self.updateWorker = e.detail.worker;
                    self.showUpdate = true;
                });
            },
            applyUpdate: function() {
                if (this.updateWorker) this.updateWorker.postMessage('SKIP_WAITING');
                this.showUpdate = false;
            },
        };
    }
</script>
