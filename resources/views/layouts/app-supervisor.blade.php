<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>K3L Monitoring</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="theme-color" content="#0891b2">
    <link rel="apple-touch-icon" href="{{ asset('images/iconpln.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-base-200 text-base-content antialiased">

<div class="min-h-screen lg:flex">

    {{-- SIDEBAR DESKTOP --}}
    <aside class="sidebar-k3l fixed inset-y-0 left-0 z-30 hidden w-[272px] lg:flex">

        {{-- Logo --}}
        <div class="flex items-center gap-3 border-b border-white/8 px-6 py-5">
            <img src="{{ asset('images/iconpln.png') }}" alt="PLN" class="h-10 w-10 rounded-xl bg-white object-contain p-1.5">
            <div>
                <p class="text-base font-extrabold tracking-tight text-white">K3L Monitoring</p>
                <p class="text-[0.65rem] font-semibold uppercase tracking-widest text-white/40">Supervisor</p>
            </div>
        </div>

        {{-- Nav links --}}
        <nav class="flex-1 space-y-1 px-4 py-5">
            @php
                $navLinks = [
                    ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => 'dashboard', 'icon' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>'],
                    ['label' => 'Data Petugas', 'route' => 'petugas.index', 'match' => 'petugas.*', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
                    ['label' => 'Lokasi', 'route' => 'lokasi.index', 'match' => 'lokasi.*', 'icon' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/>'],
                    ['label' => 'Absensi', 'route' => 'absensi.index', 'match' => 'absensi.*', 'icon' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/>'],
                ];
            @endphp

            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}" class="nav-link {{ request()->routeIs($link['match']) ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">{!! $link['icon'] !!}</svg>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- User card --}}
        <div class="border-t border-white/8 p-4">
            <div class="rounded-2xl bg-white/5 p-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary font-extrabold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-widest text-primary">Supervisor</p>
                    </div>
                </div>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-ghost btn-xs border-white/10 text-white/70 hover:bg-white/10 hover:text-white">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button onclick="return confirm('Logout?')" class="btn btn-xs w-full border-0 bg-error/20 text-error hover:bg-error/30">Logout</button>
                    </form>
                </div>
                <div class="mt-3 flex items-center justify-center">
                    <label class="swap swap-rotate">
                        <input type="checkbox" class="theme-controller" value="dark" />
                        <svg class="swap-off h-4 w-4 fill-white/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
                        <svg class="swap-on h-4 w-4 fill-white/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
                    </label>
                </div>
            </div>
        </div>
    </aside>

    {{-- MOBILE TOP BAR --}}
    <header class="sticky top-0 z-20 bg-base-100/80 px-4 py-3 backdrop-blur-2xl lg:hidden" style="border-bottom: 1px solid oklch(0 0 0 / 0.05);">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/iconpln.png') }}" alt="PLN" class="h-9 w-9 rounded-xl bg-white object-contain p-1 shadow-sm">
                <div>
                    <p class="text-sm font-extrabold tracking-tight">K3L Monitoring</p>
                    <p class="text-[0.6rem] font-semibold uppercase tracking-widest text-base-content/40">Supervisor</p>
                </div>
            </div>
            <label class="swap swap-rotate btn btn-ghost btn-sm btn-circle">
                <input type="checkbox" class="theme-controller" value="dark" />
                <svg class="swap-off h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
                <svg class="swap-on h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
            </label>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 px-4 py-5 has-btm-nav sm:px-6 lg:ml-[272px] lg:px-10 lg:py-8">
        <div class="mx-auto max-w-6xl">
            @yield('content')
        </div>
    </main>

    {{-- MOBILE BOTTOM NAV --}}
    <nav class="btm-nav-k3l">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span>Dashboard</span>
            <span class="nav-dot"></span>
        </a>
        <a href="{{ route('petugas.index') }}" class="{{ request()->routeIs('petugas.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <span>Petugas</span>
            <span class="nav-dot"></span>
        </a>
        <a href="{{ route('lokasi.index') }}" class="{{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>Lokasi</span>
            <span class="nav-dot"></span>
        </a>
        <a href="{{ route('absensi.index') }}" class="{{ request()->routeIs('absensi.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
            <span>Absensi</span>
            <span class="nav-dot"></span>
        </a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.73 12.73 1.41 1.41M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.41-1.41"/></svg>
            <span>Profil</span>
            <span class="nav-dot"></span>
        </a>
    </nav>

</div>

@stack('scripts')
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
    }
</script>
</body>
</html>
