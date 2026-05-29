@php
    $pageTitle = $pageTitle ?? 'Dashboard';
    $pageSubtitle = $pageSubtitle ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0284C7">
    <meta name="view-transition" content="same-origin">
    <title>{{ $pageTitle }} · K3L Monitoring</title>

    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-k3l-monitoring.jpeg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo-k3l-monitoring.jpeg') }}">
    <link rel="icon" type="image/jpeg" sizes="192x192" href="{{ asset('images/logo-k3l-monitoring.jpeg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="K3L">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="K3L Monitoring">
    <meta name="format-detection" content="telephone=no">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Theme: apply early to prevent flash --}}
    <script>
        (function() {
            try {
                const stored = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = stored || (prefersDark ? 'dark' : 'light');
                if (theme === 'dark') document.documentElement.classList.add('dark');
            } catch (e) {}
        })();
    </script>

    {{-- PWA: setup global state early so all components can read it --}}
    <script>
        (function() {
            const ua = navigator.userAgent;
            const isIOS = /iphone|ipad|ipod/i.test(ua) && !window.MSStream;
            const isAndroid = /android/i.test(ua);
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

            window.pwa = {
                isIOS,
                isAndroid,
                isInstalled: isStandalone,
                canPrompt: false,
                _deferredPrompt: null,

                install() {
                    if (this._deferredPrompt) {
                        this._deferredPrompt.prompt();
                        return this._deferredPrompt.userChoice.then(({ outcome }) => {
                            this._deferredPrompt = null;
                            this.canPrompt = false;
                            window.dispatchEvent(new CustomEvent('pwa-state-changed'));
                            return outcome === 'accepted';
                        });
                    }
                    return Promise.resolve(false);
                },
            };

            if (!isStandalone) {
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    window.pwa._deferredPrompt = e;
                    window.pwa.canPrompt = true;
                    window.dispatchEvent(new CustomEvent('pwa-state-changed'));
                });

                window.addEventListener('appinstalled', () => {
                    window.pwa.isInstalled = true;
                    window.pwa.canPrompt = false;
                    window.pwa._deferredPrompt = null;
                    window.dispatchEvent(new CustomEvent('pwa-state-changed'));
                });
            }
        })();
    </script>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-800 dark:bg-slate-950 text-slate-700 dark:text-slate-300 dark:text-slate-300">

    <div class="lg:flex gap-4 px-3 sm:px-5 lg:py-5">

        @include('layouts.partials.sidebar')

        <div class="flex-1 min-w-0 flex flex-col gap-3 lg:gap-4 pt-3 lg:pt-0">
            <x-topbar :title="$pageTitle" :subtitle="$pageSubtitle" />

            <main id="main-content" class="flex-1 space-y-5 with-bottom-nav lg:pb-8">
                @yield('content')
            </main>
        </div>
    </div>

    @include('layouts.partials.bottom-nav')

    @include('layouts.partials.pwa')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const clock = document.querySelector('[data-clock]');
            if (clock) {
                const fmt = new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                const dateFmt = new Intl.DateTimeFormat('id-ID', { weekday: 'short', day: '2-digit', month: 'short' });
                const tick = () => {
                    const now = new Date();
                    const t = clock.querySelector('[data-clock-time]');
                    const d = clock.querySelector('[data-clock-date]');
                    if (t) t.textContent = fmt.format(now);
                    if (d) d.textContent = dateFmt.format(now);
                };
                tick();
                setInterval(tick, 1000);
            }

            const topbar = document.querySelector('[data-topbar]');
            if (topbar) {
                const onScroll = () => {
                    if (window.scrollY > 4) topbar.classList.add('is-scrolled');
                    else topbar.classList.remove('is-scrolled');
                };
                onScroll();
                window.addEventListener('scroll', onScroll, { passive: true });
            }
        });

        // Theme toggle helper (called from dropdown buttons)
        window.setTheme = function(theme) {
            const root = document.documentElement;
            if (theme === 'system') {
                localStorage.removeItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                root.classList.toggle('dark', prefersDark);
            } else {
                localStorage.setItem('theme', theme);
                root.classList.toggle('dark', theme === 'dark');
            }
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme } }));
        };

        window.getTheme = function() {
            return localStorage.getItem('theme') || 'system';
        };

        // Listen for system preference changes when in 'system' mode
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('theme')) {
                    document.documentElement.classList.toggle('dark', e.matches);
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: 'system' } }));
                }
            });
        }
    </script>

    @include('layouts.partials.native-feel')

    @stack('scripts')
</body>
</html>
