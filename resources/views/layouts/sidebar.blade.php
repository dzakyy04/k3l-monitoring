@php
    $links = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard',
         'icon' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
        ['label' => 'Data Petugas', 'route' => 'petugas.index', 'active' => 'petugas.*',
         'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
        ['label' => 'Lokasi', 'route' => 'lokasi.index', 'active' => 'lokasi.*',
         'icon' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/>'],
        ['label' => 'Absensi', 'route' => 'absensi.index', 'active' => 'absensi.*',
         'icon' => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/>'],
    ];
@endphp

<nav class="flex h-full flex-col px-4 pt-1 pb-5">

    <ul class="menu gap-0.5 p-0">
        @foreach ($links as $link)
            <li>
                <a href="{{ route($link['route']) }}" class="{{ request()->routeIs($link['active']) ? 'active' : '' }}">
                    <span class="icon-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $link['icon'] !!}</svg>
                    </span>
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>

    {{-- User card --}}
    <div class="mt-auto space-y-3">
        <div class="mx-1 h-px bg-white/[0.06]"></div>

        <div class="rounded-2xl bg-white/[0.03] p-3.5">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-primary/70 text-sm font-extrabold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[0.8rem] font-bold text-white/90">{{ auth()->user()->name }}</p>
                    <p class="text-[0.6rem] font-semibold uppercase tracking-widest text-primary/80">{{ auth()->user()->role }}</p>
                </div>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-1.5">
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-center gap-1.5 rounded-xl bg-white/[0.05] px-3 py-2 text-[0.7rem] font-semibold text-white/50 transition hover:bg-white/[0.08] hover:text-white/80">
                    Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button onclick="return confirm('Yakin ingin logout?')" class="flex w-full items-center justify-center gap-1.5 rounded-xl bg-error/10 px-3 py-2 text-[0.7rem] font-semibold text-error/80 transition hover:bg-error/20 hover:text-error">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Theme toggle --}}
        <div class="flex items-center justify-center gap-2 pb-1">
            <label class="swap swap-rotate">
                <input type="checkbox" class="theme-controller" value="dark" />
                <svg class="swap-off h-3.5 w-3.5 fill-white/20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/></svg>
                <svg class="swap-on h-3.5 w-3.5 fill-white/20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/></svg>
            </label>
            <span class="text-[0.6rem] font-medium text-white/15">Tema</span>
        </div>
    </div>
</nav>
