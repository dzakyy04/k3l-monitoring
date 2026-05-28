@extends('layouts.app-petugas')

@section('content')

<div class="space-y-5">

    {{-- Greeting --}}
    <div>
        <p class="eyebrow">Petugas Dashboard</p>
        <h1 class="mt-1.5 text-[1.65rem] font-extrabold tracking-tight">
            Halo, {{ auth()->user()->name }} 👋
        </h1>
        <p class="mt-1 text-sm text-muted">
            Lakukan absensi, isi laporan kegiatan, dan dokumentasi APD.
        </p>
    </div>

    {{-- CTA --}}
    <a href="{{ route('absensi.create') }}" class="k3l-card flex items-center gap-4 p-5">
        <div class="stat-icon flex-shrink-0" style="background: oklch(0.65 0.18 230 / 0.1);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: oklch(0.65 0.18 230);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-extrabold tracking-tight">Input Absensi Sekarang</p>
            <p class="mt-0.5 text-xs text-muted">Geofencing GPS, checklist APD, dan dokumentasi foto</p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
    </a>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="k3l-card p-4">
            <p class="text-[0.6rem] font-bold uppercase tracking-[0.1em] text-muted">Status</p>
            <p class="mt-1.5 text-lg font-extrabold tracking-tight {{ $absensiHariIni ? 'text-success' : '' }}">
                {{ $absensiHariIni ? ucfirst($absensiHariIni->status) : '—' }}
            </p>
        </div>
        <div class="k3l-card p-4">
            <p class="text-[0.6rem] font-bold uppercase tracking-[0.1em] text-muted">Progress</p>
            <p class="mt-1.5 text-lg font-extrabold tracking-tight" style="color: oklch(0.65 0.18 230);">{{ $progressCount }}</p>
        </div>
        <div class="k3l-card p-4">
            <p class="text-[0.6rem] font-bold uppercase tracking-[0.1em] text-muted">Total</p>
            <p class="mt-1.5 text-lg font-extrabold tracking-tight">{{ $totalAbsensi }}</p>
        </div>
    </div>

    {{-- Quick link --}}
    <a href="{{ route('absensi.index') }}" class="k3l-card flex items-center gap-4 p-4">
        <div class="stat-icon flex-shrink-0" style="background: oklch(0 0 0 / 0.03);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-bold tracking-tight">Lihat Riwayat Absensi</p>
            <p class="mt-0.5 text-xs text-muted">Semua catatan kehadiran Anda</p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
    </a>

</div>

@endsection
