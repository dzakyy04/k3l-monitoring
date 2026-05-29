@php
    $role = auth()->user()->role;

    $supervisorLinks = [
        ['group' => 'Menu', 'items' => [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => ['dashboard'], 'icon' => 'layout-dashboard'],
            ['label' => 'Petugas', 'route' => 'petugas.index', 'match' => ['petugas.*'], 'icon' => 'users'],
            ['label' => 'Lokasi', 'route' => 'lokasi.index', 'match' => ['lokasi.*'], 'icon' => 'map-pin'],
            ['label' => 'Absensi', 'route' => 'absensi.index', 'match' => ['absensi.*'], 'icon' => 'clipboard-check'],
        ]],
        ['group' => 'General', 'items' => [
            ['label' => 'Profil', 'route' => 'profile.edit', 'match' => ['profile.*'], 'icon' => 'user-cog'],
        ]],
    ];

    $petugasLinks = [
        ['group' => 'Menu', 'items' => [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => ['dashboard'], 'icon' => 'layout-dashboard'],
            ['label' => 'Input Absensi', 'route' => 'absensi.create', 'match' => ['absensi.create'], 'icon' => 'plus-circle'],
            ['label' => 'Riwayat', 'route' => 'absensi.index', 'match' => ['absensi.index', 'absensi.show'], 'icon' => 'history'],
        ]],
        ['group' => 'General', 'items' => [
            ['label' => 'Profil', 'route' => 'profile.edit', 'match' => ['profile.*'], 'icon' => 'user-cog'],
        ]],
    ];

    $groups = $role === 'supervisor' ? $supervisorLinks : $petugasLinks;
@endphp

<aside class="hidden lg:flex w-64 h-[calc(100vh-2.5rem)] sticky top-5 self-start shrink-0 flex-col bg-white dark:bg-slate-900 rounded-3xl shadow-soft border border-slate-100 dark:border-white/5">
    {{-- Brand --}}
    <div class="h-16 flex items-center px-5 py-2 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <img src="{{ asset('images/logo-k3l-monitoring.jpeg') }}" alt="PLN" class="w-9 h-9 rounded-xl object-contain shrink-0">
            <span class="flex flex-col leading-tight">
                <span class="font-bold text-slate-900 dark:text-slate-100 text-[15px]">K3L Monitoring</span>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 -mt-0.5">{{ ucfirst($role) }} Suite</span>
            </span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 min-h-0 overflow-y-auto thin-scroll px-3 py-4 space-y-5" aria-label="Primary">
        @foreach($groups as $group)
            <div>
                <p class="px-3 mb-2 text-[10px] font-semibold tracking-[0.14em] text-slate-400 dark:text-slate-500 uppercase">{{ $group['group'] }}</p>
                <ul class="space-y-1">
                    @foreach($group['items'] as $item)
                        @php
                            $isActive = collect($item['match'])->contains(fn($p) => request()->routeIs($p));
                        @endphp
                        <li>
                            <a href="{{ route($item['route']) }}" class="nav-item {{ $isActive ? 'active' : '' }}">
                                @include('layouts.partials.icon', ['name' => $item['icon'], 'class' => 'w-[18px] h-[18px] text-slate-500 dark:text-slate-400 dark:text-slate-500'])
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    {{-- User card --}}
    <div class="p-3 shrink-0">
        <div class="rounded-2xl border border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-slate-800/60 p-3">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center font-bold shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-semibold text-slate-900 dark:text-slate-100 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" onclick="return confirm('Yakin ingin logout?')"
                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-[12px] font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-lg hover:border-red-300 hover:text-red-600 dark:hover:text-red-400 dark:text-red-400 cursor-pointer focus-ring transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
