@php
    $role = auth()->user()->role;

    $supervisorBottomNav = [
        ['label' => 'Beranda',  'route' => 'dashboard',      'match' => ['dashboard'],                          'icon' => 'layout-dashboard'],
        ['label' => 'Petugas',  'route' => 'petugas.index',  'match' => ['petugas.*'],                          'icon' => 'users'],
        ['label' => 'Lokasi',   'route' => 'lokasi.index',   'match' => ['lokasi.*'],                           'icon' => 'map-pin'],
        ['label' => 'Absensi',  'route' => 'absensi.index',  'match' => ['absensi.*'],                          'icon' => 'clipboard-check'],
        ['label' => 'Profil',   'route' => 'profile.edit',   'match' => ['profile.*'],                          'icon' => 'user-cog'],
    ];

    $petugasBottomNav = [
        ['label' => 'Beranda',  'route' => 'dashboard',          'match' => ['dashboard'],                       'icon' => 'layout-dashboard'],
        ['label' => 'Absen',    'route' => 'absensi.create',     'match' => ['absensi.create'],                  'icon' => 'plus-circle'],
        ['label' => 'Riwayat',  'route' => 'absensi.index',      'match' => ['absensi.index', 'absensi.show'],   'icon' => 'history'],
        ['label' => 'Profil',   'route' => 'profile.edit',       'match' => ['profile.*'],                       'icon' => 'user-cog'],
    ];

    $items = $role === 'supervisor' ? $supervisorBottomNav : $petugasBottomNav;
@endphp

<nav class="bottom-nav" aria-label="Bottom navigation">
    @foreach($items as $item)
        @php $isActive = collect($item['match'])->contains(fn($p) => request()->routeIs($p)); @endphp
        <a href="{{ route($item['route']) }}" class="bottom-nav-item {{ $isActive ? 'active' : '' }}">
            <span class="icon-wrap">
                @include('layouts.partials.icon', ['name' => $item['icon'], 'class' => 'w-[22px] h-[22px]'])
            </span>
            <span class="label">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
