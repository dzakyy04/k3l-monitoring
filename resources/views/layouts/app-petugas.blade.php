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
    <title>{{ $pageTitle }} · K3L Monitoring</title>

    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/iconpln.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="mobile-web-app-capable" content="yes">

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
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-800 dark:bg-slate-950 text-slate-700 dark:text-slate-300 dark:text-slate-300">

    <div class="lg:flex gap-4 px-3 sm:px-5 lg:py-5">

        @include('layouts.partials.sidebar')

        <div class="flex-1 min-w-0 flex flex-col gap-3 lg:gap-4 pt-3 lg:pt-0">
            <x-topbar :title="$pageTitle" :subtitle="$pageSubtitle" />

            <main class="flex-1 space-y-5 with-bottom-nav lg:pb-8">
                @yield('content')
            </main>
        </div>
    </div>

    @include('layouts.partials.bottom-nav')

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

        // Theme toggle helper
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

        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('theme')) {
                    document.documentElement.classList.toggle('dark', e.matches);
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: 'system' } }));
                }
            });
        }

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
        }
    </script>

    @stack('scripts')
</body>
</html>
