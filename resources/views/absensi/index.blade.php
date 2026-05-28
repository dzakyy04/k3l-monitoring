@php
    $pageTitle = 'Absensi';
    $pageSubtitle = 'Riwayat kehadiran petugas';
    $layout = auth()->user()->role === 'supervisor' ? 'layouts.app-supervisor' : 'layouts.app-petugas';
@endphp
@extends($layout)

@section('content')

{{-- Title row --}}
<section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <p class="eyebrow">Data Kehadiran</p>
        <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">Riwayat Absensi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">{{ $absensi->count() }} catatan dari {{ \Carbon\Carbon::parse($tanggalDari)->translatedFormat('d M Y') }} sampai {{ \Carbon\Carbon::parse($tanggalSampai)->translatedFormat('d M Y') }}.</p>
    </div>
    <div class="flex items-center gap-2">
        @if(auth()->user()->role === 'petugas')
            <a href="{{ route('absensi.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors shadow-soft">
                <x-icon name="plus" class="w-4 h-4" />
                <span>Input Absensi</span>
            </a>
        @endif
        @if(auth()->user()->role === 'supervisor')
            <a href="{{ route('absensi.download', ['tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors">
                <x-icon name="download" class="w-4 h-4" />
                <span>Export Excel</span>
            </a>
        @endif
    </div>
</section>

@if(session('success'))
    <x-alert type="success" :message="session('success')" />
@endif

{{-- Filter --}}
<form method="GET" action="{{ route('absensi.index') }}" class="surface-card p-5">
    <div class="flex flex-col sm:flex-row sm:items-end gap-3">
        <div class="flex-1">
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Tanggal Dari</label>
            <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring" />
        </div>
        <div class="flex-1">
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Tanggal Sampai</label>
            <input type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring" />
        </div>
        <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
            <x-icon name="search" class="w-4 h-4" />
            Filter
        </button>
    </div>
</form>

{{-- Mobile cards --}}
<div class="space-y-3 lg:hidden">
    @forelse($absensi as $item)
        <a href="{{ route('absensi.show', $item) }}" class="block surface-card overflow-hidden hover:border-brand-200 transition-colors">
            <div class="flex gap-3 p-3">
                {{-- Thumbnail --}}
                @if($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto absensi"
                         loading="lazy"
                         class="w-20 h-20 rounded-xl object-cover bg-slate-100 dark:bg-slate-800 shrink-0">
                @else
                    <div class="w-20 h-20 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500 shrink-0">
                        <x-icon name="camera" class="w-6 h-6" />
                    </div>
                @endif

                {{-- Info --}}
                <div class="flex-1 min-w-0 flex flex-col">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $item->user->name ?? '—' }}</p>
                        @if($item->status === 'progress')
                            <span class="pill pill-info"><span class="dot"></span>Progress</span>
                        @else
                            <span class="pill pill-muted"><span class="dot"></span>Standby</span>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ optional($item->tanggal)->translatedFormat('d M Y') }} · <span class="font-mono-data">{{ substr($item->jam, 0, 5) }}</span>
                    </p>
                    @if($item->lokasi)
                        <p class="mt-auto pt-2 text-xs text-slate-600 dark:text-slate-400 truncate flex items-center gap-1.5">
                            <x-icon name="map-pin" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 shrink-0" />
                            <span class="truncate">{{ $item->lokasi }}</span>
                        </p>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="surface-card p-10 text-center">
            <span class="mx-auto w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-3">
                <x-icon name="clipboard-check" class="w-5 h-5" />
            </span>
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada data absensi</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Coba ubah rentang tanggal filter.</p>
        </div>
    @endforelse
</div>

{{-- Desktop table --}}
<article class="surface-card hidden lg:block overflow-hidden">
    <div class="overflow-x-auto thin-scroll">
        <table class="w-full text-sm">
            <thead class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-800/60/60 dark:bg-slate-800/40">
                <tr class="text-left">
                    <th class="font-semibold px-5 py-3 w-[72px]">Foto</th>
                    <th class="font-semibold px-5 py-3">Petugas</th>
                    <th class="font-semibold px-5 py-3">Tanggal</th>
                    <th class="font-semibold px-5 py-3">Jam</th>
                    <th class="font-semibold px-5 py-3">Lokasi</th>
                    <th class="font-semibold px-5 py-3">Status</th>
                    <th class="font-semibold px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($absensi as $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60">
                        <td class="px-5 py-3">
                            @if($item->foto)
                                <a href="{{ asset('storage/' . $item->foto) }}" target="_blank" rel="noopener"
                                   class="block group relative">
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto absensi"
                                         loading="lazy"
                                         class="w-12 h-12 rounded-lg object-cover bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200 dark:ring-white/10 group-hover:ring-brand-300 transition-shadow">
                                </a>
                            @else
                                <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500">
                                    <x-icon name="camera" class="w-4 h-4" />
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($item->user->name ?? 'NA', 0, 2)) }}
                                </span>
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->user->name ?? '—' }}</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500">{{ $item->lokasiData->nama_lokasi ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-slate-700 dark:text-slate-300">{{ optional($item->tanggal)->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3 font-mono-data text-slate-700 dark:text-slate-300">{{ substr($item->jam, 0, 5) }}</td>
                        <td class="px-5 py-3 text-slate-700 dark:text-slate-300 max-w-[240px] truncate">{{ $item->lokasi ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @if($item->status === 'progress')
                                <span class="pill pill-info"><span class="dot"></span>Progress</span>
                            @else
                                <span class="pill pill-muted"><span class="dot"></span>Standby</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('absensi.show', $item) }}"
                                   class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 dark:text-slate-500 hover:text-brand-700 dark:hover:text-brand-300 dark:text-brand-300 hover:bg-slate-100 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer focus-ring"
                                   aria-label="Detail">
                                    <x-icon name="eye" class="w-4 h-4" />
                                </a>
                                @if(auth()->user()->role === 'supervisor')
                                    <a href="{{ route('absensi.edit', $item) }}"
                                       class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 dark:text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer focus-ring"
                                       aria-label="Edit">
                                        <x-icon name="pencil" class="w-4 h-4" />
                                    </a>
                                    <form action="{{ route('absensi.destroy', $item) }}" method="POST" class="inline-flex">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus data absensi ini?')"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 dark:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer focus-ring"
                                                aria-label="Hapus">
                                            <x-icon name="trash-2" class="w-4 h-4" />
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada data absensi</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Coba ubah rentang tanggal filter.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</article>

@endsection
