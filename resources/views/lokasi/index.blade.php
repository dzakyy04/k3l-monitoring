@php $pageTitle = 'Lokasi'; $pageSubtitle = 'Polygon area geofence'; @endphp
@extends('layouts.app-supervisor')

@section('content')

<section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <p class="eyebrow">Manajemen Geofence</p>
        <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">Data Lokasi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">{{ $lokasi->count() }} area terdaftar untuk validasi absensi.</p>
    </div>
    <a href="{{ route('lokasi.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors shadow-soft self-start">
        <x-icon name="plus" class="w-4 h-4" />
        Tambah Lokasi
    </a>
</section>

@if(session('success'))
    <x-alert type="success" :message="session('success')" />
@endif

{{-- Cards grid (mobile + tablet) --}}
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 lg:hidden">
    @forelse($lokasi as $item)
        <article class="surface-card p-5">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 truncate">{{ $item->nama_lokasi }}</h3>
                    <p class="text-[11px] font-mono-data text-slate-500 dark:text-slate-400 dark:text-slate-500 truncate">{{ $item->latitude }}, {{ $item->longitude }}</p>
                </div>
                @if($item->polygon && count($item->polygon) >= 3)
                    <span class="pill pill-info"><span class="dot"></span>{{ count($item->polygon) }} titik</span>
                @else
                    <span class="pill pill-muted"><span class="dot"></span>Tidak ada polygon</span>
                @endif
            </div>
            <div class="mt-4 flex items-center gap-1 border-t border-slate-100 dark:border-white/5 pt-3">
                <a href="{{ route('lokasi.edit', $item) }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-500/10 dark:bg-amber-500/10 rounded-full cursor-pointer focus-ring">
                    <x-icon name="pencil" class="w-3.5 h-3.5" />Edit
                </a>
                <form action="{{ route('lokasi.destroy', $item) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Hapus lokasi {{ $item->nama_lokasi }}?')"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-500/10 dark:bg-red-500/10 rounded-full cursor-pointer focus-ring">
                        <x-icon name="trash-2" class="w-3.5 h-3.5" />Hapus
                    </button>
                </form>
            </div>
        </article>
    @empty
        <div class="surface-card p-10 text-center sm:col-span-2 xl:col-span-3">
            <span class="mx-auto w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-3">
                <x-icon name="map-pin" class="w-5 h-5" />
            </span>
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada lokasi terdaftar</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Tambahkan area geofence pertama Anda.</p>
        </div>
    @endforelse
</section>

{{-- Desktop table --}}
<article class="surface-card hidden lg:block overflow-hidden">
    <div class="overflow-x-auto thin-scroll">
        <table class="w-full text-sm">
            <thead class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-800/60/60 dark:bg-slate-800/40">
                <tr class="text-left">
                    <th class="font-semibold px-5 py-3">Nama Lokasi</th>
                    <th class="font-semibold px-5 py-3">Pusat (Lat, Lng)</th>
                    <th class="font-semibold px-5 py-3">Tipe</th>
                    <th class="font-semibold px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($lokasi as $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-sky-50 dark:bg-sky-500/10 text-brand-700 dark:text-brand-300 flex items-center justify-center">
                                    <x-icon name="map-pin" class="w-4 h-4" />
                                </span>
                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->nama_lokasi }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 font-mono-data text-slate-700 dark:text-slate-300">{{ $item->latitude }}, {{ $item->longitude }}</td>
                        <td class="px-5 py-3">
                            @if($item->polygon && count($item->polygon) >= 3)
                                <span class="pill pill-info"><span class="dot"></span>Polygon · {{ count($item->polygon) }} titik</span>
                            @else
                                <span class="pill pill-muted"><span class="dot"></span>Tidak ada polygon</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('lokasi.edit', $item) }}"
                                   class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 dark:text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer focus-ring"
                                   aria-label="Edit">
                                    <x-icon name="pencil" class="w-4 h-4" />
                                </a>
                                <form action="{{ route('lokasi.destroy', $item) }}" method="POST" class="inline-flex">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus lokasi {{ $item->nama_lokasi }}?')"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 dark:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer focus-ring"
                                            aria-label="Hapus">
                                        <x-icon name="trash-2" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada lokasi terdaftar</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</article>

@endsection
