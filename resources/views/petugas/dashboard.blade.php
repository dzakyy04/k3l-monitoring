@php $pageTitle = 'Dashboard'; $pageSubtitle = 'Aktivitas absensi Anda hari ini'; @endphp
@extends('layouts.app-petugas')

@section('content')

{{-- Title row --}}
<section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <p class="eyebrow">Petugas Dashboard</p>
        <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">Halo, {{ explode(' ', auth()->user()->name)[0] }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Lakukan absensi, lengkapi checklist APD, dan dokumentasikan kegiatan.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('absensi.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors shadow-soft">
            <x-icon name="plus" class="w-4 h-4" />
            <span>Input Absensi</span>
        </a>
        <a href="{{ route('absensi.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors">
            <x-icon name="history" class="w-4 h-4" />
            <span>Riwayat</span>
        </a>
    </div>
</section>

{{-- KPI Row --}}
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <article class="kpi-card highlight">
        <div class="flex items-start justify-between">
            <p class="label text-sm font-semibold">Status Hari Ini</p>
            <span class="kpi-arrow"><x-icon name="activity" class="w-4 h-4" /></span>
        </div>
        <p class="kpi-value text-3xl font-bold mt-6">{{ $absensiHariIni ? ucfirst($absensiHariIni->status) : 'Belum Absen' }}</p>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="chip inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full">
                <x-icon name="clock" class="w-3 h-3" />
                {{ $absensiHariIni ? substr($absensiHariIni->jam, 0, 5) : '—' }}
            </span>
            <span class="sub text-xs">{{ today()->format('d M Y') }}</span>
        </div>
    </article>

    <article class="kpi-card">
        <div class="flex items-start justify-between">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Bulan Ini</p>
            <span class="kpi-arrow"><x-icon name="calendar" class="w-4 h-4" /></span>
        </div>
        <p class="kpi-value text-4xl font-bold text-slate-900 dark:text-slate-100 mt-6">{{ $absensiBulanIni }}</p>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 px-2 py-0.5 rounded-full">
                <x-icon name="trending-up" class="w-3 h-3" />Aktif
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Catatan {{ now()->translatedFormat('F') }}</span>
        </div>
    </article>

    <article class="kpi-card">
        <div class="flex items-start justify-between">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Total Progress</p>
            <span class="kpi-arrow"><x-icon name="check-circle" class="w-4 h-4" /></span>
        </div>
        <p class="kpi-value text-4xl font-bold text-slate-900 dark:text-slate-100 mt-6">{{ $progressCount }}</p>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-700 bg-sky-50 dark:bg-sky-500/10 border border-sky-100 px-2 py-0.5 rounded-full">
                <x-icon name="circle" class="w-3 h-3" />Selesai
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Pekerjaan dilaporkan</span>
        </div>
    </article>

    <article class="kpi-card">
        <div class="flex items-start justify-between">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Total Absensi</p>
            <span class="kpi-arrow"><x-icon name="clipboard-check" class="w-4 h-4" /></span>
        </div>
        <p class="kpi-value text-4xl font-bold text-slate-900 dark:text-slate-100 mt-6">{{ $totalAbsensi }}</p>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 px-2 py-0.5 rounded-full">
                <x-icon name="file-text" class="w-3 h-3" />Riwayat
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Sepanjang waktu</span>
        </div>
    </article>
</section>

{{-- Status today + Recent --}}
<section class="grid grid-cols-1 lg:grid-cols-12 gap-4">

    {{-- Today's status detail --}}
    <article class="surface-card p-5 lg:col-span-5">
        <header class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Absensi Hari Ini</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">{{ today()->translatedFormat('l, d F Y') }}</p>
            </div>
            @if($absensiHariIni)
                @if($absensiHariIni->status === 'progress')
                    <span class="pill pill-info"><span class="dot"></span>Progress</span>
                @else
                    <span class="pill pill-muted"><span class="dot"></span>Standby</span>
                @endif
            @else
                <span class="pill pill-warning"><span class="dot"></span>Belum Absen</span>
            @endif
        </header>

        @if($absensiHariIni)
            <div class="space-y-3 mt-4">
                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                    <span class="w-9 h-9 rounded-xl bg-sky-100 text-brand-700 dark:text-brand-300 flex items-center justify-center shrink-0">
                        <x-icon name="map-pin" class="w-4 h-4" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase">Lokasi Geofence</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 mt-0.5">{{ $absensiHariIni->lokasiData->nama_lokasi ?? '—' }}</p>
                        @if($absensiHariIni->lokasi)
                            <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 truncate mt-0.5">{{ $absensiHariIni->lokasi }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                    <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
                        <x-icon name="clock" class="w-4 h-4" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase">Waktu Absen</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 mt-0.5 font-mono-data">{{ substr($absensiHariIni->jam, 0, 5) }} WIB</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('absensi.show', $absensiHariIni) }}"
               class="mt-5 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/20 hover:bg-brand-100 dark:bg-brand-900/40 rounded-full cursor-pointer focus-ring transition-colors">
                <x-icon name="eye" class="w-4 h-4" />
                Lihat Detail
            </a>
        @else
            <div class="text-center py-8">
                <span class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-3">
                    <x-icon name="clipboard-check" class="w-6 h-6" />
                </span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Anda belum absen hari ini</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Pastikan GPS aktif dan berada di area lokasi.</p>
                <a href="{{ route('absensi.create') }}"
                   class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                    <x-icon name="plus-circle" class="w-4 h-4" />
                    Absen Sekarang
                </a>
            </div>
        @endif
    </article>

    {{-- Recent history --}}
    <article class="surface-card lg:col-span-7 overflow-hidden">
        <header class="flex items-center justify-between gap-3 px-5 py-4 border-b border-slate-100 dark:border-white/5">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Riwayat Terbaru</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">5 absensi terakhir Anda</p>
            </div>
            <a href="{{ route('absensi.index') }}" class="text-xs font-semibold text-brand-700 dark:text-brand-300 hover:text-brand-800 dark:text-brand-300 cursor-pointer">Lihat semua</a>
        </header>

        @if($riwayatTerbaru->isEmpty())
            <div class="px-5 py-12 text-center">
                <span class="mx-auto w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-3">
                    <x-icon name="history" class="w-5 h-5" />
                </span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada riwayat absensi</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Riwayat akan muncul setelah absen pertama.</p>
            </div>
        @else
            <ul class="divide-y divide-slate-100 dark:divide-white/5">
                @foreach($riwayatTerbaru as $item)
                    <li>
                        <a href="{{ route('absensi.show', $item) }}" class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60 cursor-pointer">
                            <span class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-500/10 text-brand-700 dark:text-brand-300 flex items-center justify-center shrink-0">
                                <x-icon name="map-pin" class="w-4 h-4" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $item->lokasiData->nama_lokasi ?? '—' }}</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500">
                                    {{ optional($item->tanggal)->translatedFormat('d M Y') }} · {{ substr($item->jam, 0, 5) }}
                                </p>
                            </div>
                            @if($item->status === 'progress')
                                <span class="pill pill-info"><span class="dot"></span>Progress</span>
                            @else
                                <span class="pill pill-muted"><span class="dot"></span>Standby</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </article>
</section>

@endsection
