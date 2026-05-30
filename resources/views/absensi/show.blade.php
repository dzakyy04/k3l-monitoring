@php
    $pageTitle = 'Detail Absensi';
    $layout = auth()->user()->role === 'supervisor' ? 'layouts.app-supervisor' : 'layouts.app-petugas';
@endphp
@extends($layout)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
@endpush

@section('content')

{{-- Title row --}}
<section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <p class="eyebrow">Detail Kehadiran</p>
        <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">{{ $absensi->user->name ?? 'Petugas' }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">{{ optional($absensi->tanggal)->translatedFormat('l, d F Y') }} pukul <span class="font-mono-data">{{ substr($absensi->jam, 0, 5) }}</span> WIB</p>
    </div>
    <div class="flex items-center gap-2">
        @if($absensi->status === 'progress')
            <span class="pill pill-info"><span class="dot"></span>Progress</span>
        @else
            <span class="pill pill-muted"><span class="dot"></span>Standby</span>
        @endif
        <a href="{{ route('absensi.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors">
            <x-icon name="arrow-left" class="w-4 h-4" />
            Kembali
        </a>
    </div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Info card --}}
    <article class="surface-card overflow-hidden">
        @if($absensi->foto)
            <img src="{{ asset('storage/' . $absensi->foto) }}" alt="Foto absensi" class="w-full h-auto block">
        @endif
        <div class="p-5 space-y-4">
            <div>
                <p class="text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase">Lokasi Geofence</p>
                <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $absensi->lokasiData->nama_lokasi ?? '—' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase">Lokasi Pekerjaan</p>
                <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $absensi->lokasi ?? '—' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase">Uraian Kegiatan</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $absensi->uraian ?? '—' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase">Checklist APD</p>
                <div class="mt-2 flex flex-wrap gap-1.5">
                    @forelse($absensi->checklist_apd ?? [] as $apd)
                        <span class="pill pill-info"><x-icon name="shield-check" class="w-3 h-3" />{{ $apd }}</span>
                    @empty
                        <span class="text-sm text-slate-400 dark:text-slate-500">Tidak ada APD tercatat</span>
                    @endforelse
                </div>
            </div>
        </div>
    </article>

    {{-- Map card --}}
    <article class="surface-card p-5">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Peta Lokasi</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Titik absensi dan area geofence terdaftar.</p>

        <div id="map" class="mt-4 min-h-[360px] rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800"></div>

        <div class="mt-4 flex flex-col sm:flex-row gap-2">
            @if($absensi->latitude && $absensi->longitude)
                <a href="https://www.google.com/maps?q={{ $absensi->latitude }},{{ $absensi->longitude }}" target="_blank" rel="noopener"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                    <x-icon name="external-link" class="w-4 h-4" />
                    Buka di Google Maps
                </a>
            @endif
            @if(auth()->user()->role === 'supervisor')
                <a href="{{ route('absensi.edit', $absensi) }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors">
                    <x-icon name="pencil" class="w-4 h-4" />
                    Edit Data
                </a>
            @endif
        </div>
    </article>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const lat = {{ $absensi->latitude ? (float) $absensi->latitude : 'null' }};
const lng = {{ $absensi->longitude ? (float) $absensi->longitude : 'null' }};
const fenceLat = {{ $absensi->lokasiData?->latitude ? (float) $absensi->lokasiData->latitude : 'null' }};
const fenceLng = {{ $absensi->lokasiData?->longitude ? (float) $absensi->lokasiData->longitude : 'null' }};
const radius = {{ $absensi->lokasiData?->radius ? (int) $absensi->lokasiData->radius : 100 }};
const polygon = @json($absensi->lokasiData?->polygon);

const center = lat && lng ? [lat, lng] : (fenceLat && fenceLng ? [fenceLat, fenceLng] : [-2.990934, 104.756554]);
const map = L.map('map').setView(center, 16);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

if (polygon && polygon.length >= 3) {
    L.polygon(polygon.map(p => [p[0], p[1]]), { color: '#0284C7', fillColor: '#7DD3FC', fillOpacity: 0.2 }).addTo(map).bindPopup('Area Geofence');
} else if (fenceLat && fenceLng) {
    L.circle([fenceLat, fenceLng], { radius: radius, color: '#0284C7', fillColor: '#7DD3FC', fillOpacity: 0.2 }).addTo(map).bindPopup('Area Geofence');
}

if (lat && lng) {
    L.marker([lat, lng]).addTo(map).bindPopup('Lokasi Absensi').openPopup();
}
</script>
@endpush
