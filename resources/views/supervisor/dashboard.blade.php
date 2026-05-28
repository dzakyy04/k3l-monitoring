@php $pageTitle = 'Dashboard'; $pageSubtitle = 'Ringkasan monitoring K3L harian'; @endphp
@extends('layouts.app-supervisor')

@section('content')

{{-- Title row --}}
<section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <p class="eyebrow">Supervisor Dashboard</p>
        <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">Ringkasan Monitoring K3L</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Pantau kehadiran petugas, aktivitas progress, dan kelola data operasional.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('petugas.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors shadow-soft">
            <x-icon name="plus" class="w-4 h-4" />
            <span>Tambah Petugas</span>
        </a>
        <a href="{{ route('absensi.download', ['tanggal_dari' => today()->toDateString(), 'tanggal_sampai' => today()->toDateString()]) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors">
            <x-icon name="download" class="w-4 h-4" />
            <span>Laporan Hari Ini</span>
        </a>
    </div>
</section>

{{-- KPI Row --}}
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <article class="kpi-card highlight">
        <div class="flex items-start justify-between">
            <p class="label text-sm font-semibold">Total Petugas</p>
            <a href="{{ route('petugas.index') }}" class="kpi-arrow" aria-label="Lihat petugas">
                <x-icon name="arrow-up-right" class="w-4 h-4" />
            </a>
        </div>
        <p class="kpi-value text-4xl font-bold mt-6">{{ $totalPetugas }}</p>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="chip inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full">
                <x-icon name="users-2" class="w-3 h-3" />{{ $totalSupervisor }} supervisor
            </span>
            <span class="sub text-xs">Aktif di sistem</span>
        </div>
    </article>

    <article class="kpi-card">
        <div class="flex items-start justify-between">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Absensi Hari Ini</p>
            <a href="{{ route('absensi.index') }}" class="kpi-arrow" aria-label="Lihat absensi">
                <x-icon name="arrow-up-right" class="w-4 h-4" />
            </a>
        </div>
        <p class="kpi-value text-4xl font-bold text-slate-900 dark:text-slate-100 mt-6">{{ $absensiHariIni }}</p>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 px-2 py-0.5 rounded-full">
                <x-icon name="trending-up" class="w-3 h-3" />Live
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Tercatat hari ini</span>
        </div>
    </article>

    <article class="kpi-card">
        <div class="flex items-start justify-between">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Status Progress</p>
            <span class="kpi-arrow"><x-icon name="activity" class="w-4 h-4" /></span>
        </div>
        <p class="kpi-value text-4xl font-bold text-slate-900 dark:text-slate-100 mt-6">{{ $progressHariIni }}</p>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 px-2 py-0.5 rounded-full">
                <x-icon name="circle" class="w-3 h-3" />{{ $standbyHariIni }} standby
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Sedang bekerja</span>
        </div>
    </article>

    <article class="kpi-card">
        <div class="flex items-start justify-between">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Lokasi Geofence</p>
            <a href="{{ route('lokasi.index') }}" class="kpi-arrow" aria-label="Lihat lokasi">
                <x-icon name="arrow-up-right" class="w-4 h-4" />
            </a>
        </div>
        <p class="kpi-value text-4xl font-bold text-slate-900 dark:text-slate-100 mt-6">{{ $totalLokasi }}</p>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-700 bg-sky-50 dark:bg-sky-500/10 border border-sky-100 px-2 py-0.5 rounded-full">
                <x-icon name="map-pin" class="w-3 h-3" />Aktif
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Area terdaftar</span>
        </div>
    </article>
</section>

{{-- Quick actions + Recent --}}
<section class="grid grid-cols-1 lg:grid-cols-12 gap-4">

    {{-- Quick actions --}}
    <article class="surface-card p-5 lg:col-span-4">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Aksi Cepat</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Pintasan ke fitur utama</p>

        <div class="mt-4 space-y-2.5">
            <a href="{{ route('petugas.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60 transition-colors cursor-pointer group">
                <span class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-500/10 text-brand-700 dark:text-brand-300 flex items-center justify-center shrink-0">
                    <x-icon name="users" class="w-5 h-5" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Kelola Petugas</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500">Tambah, edit, atau hapus akun</p>
                </div>
                <x-icon name="arrow-right" class="w-4 h-4 text-slate-400 dark:text-slate-500 group-hover:text-brand-600" />
            </a>

            <a href="{{ route('lokasi.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60 transition-colors cursor-pointer group">
                <span class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
                    <x-icon name="map-pin" class="w-5 h-5" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Lokasi Geofence</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500">Atur polygon area absensi</p>
                </div>
                <x-icon name="arrow-right" class="w-4 h-4 text-slate-400 dark:text-slate-500 group-hover:text-brand-600" />
            </a>

            <a href="{{ route('absensi.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60 transition-colors cursor-pointer group">
                <span class="w-10 h-10 rounded-xl bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-300 flex items-center justify-center shrink-0">
                    <x-icon name="clipboard-check" class="w-5 h-5" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Data Absensi</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500">Riwayat & laporan kehadiran</p>
                </div>
                <x-icon name="arrow-right" class="w-4 h-4 text-slate-400 dark:text-slate-500 group-hover:text-brand-600" />
            </a>

            <a href="{{ route('absensi.download', ['tanggal_dari' => today()->toDateString(), 'tanggal_sampai' => today()->toDateString()]) }}"
               class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60 transition-colors cursor-pointer group">
                <span class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0">
                    <x-icon name="download" class="w-5 h-5" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Export Excel</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500">Laporan absensi hari ini</p>
                </div>
                <x-icon name="arrow-right" class="w-4 h-4 text-slate-400 dark:text-slate-500 group-hover:text-brand-600" />
            </a>
        </div>
    </article>

    {{-- Recent absensi --}}
    <article class="surface-card lg:col-span-8 overflow-hidden">
        <header class="flex items-center justify-between gap-3 px-5 py-4 border-b border-slate-100 dark:border-white/5">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Absensi Terbaru</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">{{ $absensiTerbaru->count() }} catatan terakhir hari ini</p>
            </div>
            <a href="{{ route('absensi.index') }}" class="text-xs font-semibold text-brand-700 dark:text-brand-300 hover:text-brand-800 dark:text-brand-300 cursor-pointer">Lihat semua</a>
        </header>

        @if($absensiTerbaru->isEmpty())
            <div class="px-5 py-12 text-center">
                <span class="mx-auto w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-3">
                    <x-icon name="clipboard-check" class="w-5 h-5" />
                </span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada absensi hari ini</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Catatan akan muncul saat petugas mulai absen.</p>
            </div>
        @else
            <div class="overflow-x-auto thin-scroll">
                <table class="w-full text-sm">
                    <thead class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-800/60/60 dark:bg-slate-800/40">
                        <tr class="text-left">
                            <th class="font-semibold px-5 py-3">Petugas</th>
                            <th class="font-semibold px-5 py-3">Lokasi</th>
                            <th class="font-semibold px-5 py-3">Jam</th>
                            <th class="font-semibold px-5 py-3">Status</th>
                            <th class="font-semibold px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach($absensiTerbaru as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center text-xs font-bold">
                                            {{ strtoupper(substr($item->user->name ?? 'NA', 0, 2)) }}
                                        </span>
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->user->name ?? '—' }}</p>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500">{{ $item->lokasiData->nama_lokasi ?? 'Tanpa lokasi' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-slate-700 dark:text-slate-300 max-w-[200px] truncate">{{ $item->lokasi ?? '—' }}</td>
                                <td class="px-5 py-3 font-mono-data text-slate-700 dark:text-slate-300">{{ $item->jam ? substr($item->jam, 0, 5) : '—' }}</td>
                                <td class="px-5 py-3">
                                    @if($item->status === 'progress')
                                        <span class="pill pill-info"><span class="dot"></span>Progress</span>
                                    @else
                                        <span class="pill pill-muted"><span class="dot"></span>Standby</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('absensi.show', $item) }}" class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/20 hover:bg-brand-100 dark:bg-brand-900/40 rounded-full cursor-pointer focus-ring">
                                        <x-icon name="eye" class="w-3 h-3" />Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </article>
</section>

@endsection
