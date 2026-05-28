@extends('layouts.app-supervisor')

@section('content')

<div class="space-y-5">

    {{-- Greeting --}}
    <div>
        <p class="eyebrow">Supervisor Dashboard</p>
        <h1 class="mt-1.5 text-[1.65rem] font-extrabold tracking-tight">
            Ringkasan Monitoring K3L
        </h1>
        <p class="mt-1 text-sm text-muted">
            Pantau kehadiran petugas, aktivitas progress, dan kelola data operasional.
        </p>
    </div>

    {{-- Quick actions --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <a href="{{ route('petugas.index') }}" class="k3l-card flex items-center gap-4 p-5">
            <div class="stat-icon flex-shrink-0" style="background: oklch(0.65 0.18 230 / 0.1);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: oklch(0.65 0.18 230);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-extrabold tracking-tight">Kelola Petugas</p>
                <p class="mt-0.5 text-xs text-muted">Tambah, edit, atau hapus akun petugas</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
        </a>

        <a href="{{ route('absensi.index') }}" class="k3l-card flex items-center gap-4 p-5">
            <div class="stat-icon flex-shrink-0" style="background: oklch(0.72 0.19 155 / 0.1);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: oklch(0.72 0.19 155);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-extrabold tracking-tight">Data Absensi</p>
                <p class="mt-0.5 text-xs text-muted">Lihat riwayat absensi dan download laporan</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="k3l-card p-4 lg:p-5">
            <p class="text-[0.6rem] font-bold uppercase tracking-[0.1em] text-muted">Petugas</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight lg:text-3xl">{{ $totalPetugas }}</p>
        </div>
        <div class="k3l-card p-4 lg:p-5">
            <p class="text-[0.6rem] font-bold uppercase tracking-[0.1em] text-muted">Absen Hari Ini</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-success lg:text-3xl">{{ $absensiHariIni }}</p>
        </div>
        <div class="k3l-card p-4 lg:p-5">
            <p class="text-[0.6rem] font-bold uppercase tracking-[0.1em] text-muted">Progress</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight lg:text-3xl" style="color: oklch(0.65 0.18 230);">{{ $progressHariIni }}</p>
        </div>
    </div>

    {{-- Quick links --}}
    <a href="{{ route('lokasi.index') }}" class="k3l-card flex items-center gap-4 p-4">
        <div class="stat-icon flex-shrink-0" style="background: oklch(0 0 0 / 0.03);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-bold tracking-tight">Kelola Lokasi Geofencing</p>
            <p class="mt-0.5 text-xs text-muted">Atur polygon area absensi petugas</p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
    </a>

    <a href="{{ route('absensi.download', ['tanggal_dari' => today()->toDateString(), 'tanggal_sampai' => today()->toDateString()]) }}" class="k3l-card flex items-center gap-4 p-4">
        <div class="stat-icon flex-shrink-0" style="background: oklch(0 0 0 / 0.03);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-bold tracking-tight">Download Laporan Hari Ini</p>
            <p class="mt-0.5 text-xs text-muted">Export data absensi ke format Excel</p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
    </a>

</div>

@endsection
