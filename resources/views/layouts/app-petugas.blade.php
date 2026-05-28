<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>K3L Monitoring - Petugas</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="theme-color" content="#0891b2">
    <link rel="apple-touch-icon" href="{{ asset('images/iconpln.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-base-200 text-base-content antialiased">

<div class="min-h-screen lg:flex">

    <aside class="sidebar-k3l fixed inset-y-0 left-0 z-30 hidden w-[272px] flex-col overflow-y-auto lg:flex">
        <div class="px-6 pb-2 pt-7">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 p-1.5">
                    <img src="{{ asset('images/iconpln.png') }}" alt="PLN" class="h-full w-full object-contain">
                </div>
                <div>
                    <p class="text-sm font-extrabold tracking-tight text-white">K3L Monitoring</p>
                    <p class="text-[0.6rem] font-semibold uppercase tracking-widest text-white/30">Petugas</p>
                </div>
            </div>
        </div>
        <div class="mx-5 my-3 h-px bg-white/[0.06]"></div>
        @include('layouts.sidebar-petugas')
    </aside>

    <header class="topbar-k3l lg:hidden">
        <div class="flex w-full items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-base-200 p-1">
                    <img src="{{ asset('images/iconpln.png') }}" alt="PLN" class="h-full w-full object-contain">
                </div>
                <p class="text-[0.8rem] font-extrabold tracking-tight">K3L Monitoring</p>
            </div>
            <label class="swap swap-rotate">
                <input type="checkbox" class="theme-controller" value="dark" />
                <svg class="swap-off h-[18px] w-[18px] fill-current opacity-40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
                <svg class="swap-on h-[18px] w-[18px] fill-current opacity-40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
            </label>
        </div>
    </header>

    <main class="page-enter flex-1 px-4 py-5 has-btm-nav sm:px-5 lg:ml-[272px] lg:px-8 lg:py-7 lg:pb-7">
        <div class="mx-auto max-w-6xl">
            @yield('content')
        </div>
    </main>

    <nav class="btm-nav-k3l">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Home</span><span class="nav-dot"></span>
        </a>
        <a href="{{ route('absensi.create') }}" class="{{ request()->routeIs('absensi.create') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
            <span>Absen</span><span class="nav-dot"></span>
        </a>
        <a href="{{ route('absensi.index') }}" class="{{ request()->routeIs('absensi.index') || request()->routeIs('absensi.show') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
            <span>Riwayat</span><span class="nav-dot"></span>
        </a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Profil</span><span class="nav-dot"></span>
        </a>
    </nav>
</div>

@stack('scripts')
<script>if ('serviceWorker' in navigator) { window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js')); }</script>
</body>
</html>
