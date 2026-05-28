@extends(auth()->user()->role === 'supervisor' ? 'layouts.app-supervisor' : 'layouts.app-petugas')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

<div class="mx-auto max-w-6xl space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="eyebrow">Detail Absensi</p>
            <h1 class="mt-1 text-[1.65rem] font-extrabold tracking-tight">{{ $absensi->user->name ?? 'Petugas' }}</h1>
            <p class="mt-1 text-sm text-muted">{{ optional($absensi->tanggal)->format('d M Y') }} pukul {{ $absensi->jam ? substr($absensi->jam, 0, 5) : '-' }}</p>
        </div>

        <span class="badge {{ $absensi->status === 'progress' ? 'badge-success' : 'badge-warning' }} badge-lg font-bold uppercase">{{ ucfirst($absensi->status) }}</span>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        {{-- Info card --}}
        <div class="k3l-card-static overflow-hidden">
            @if($absensi->foto)
                <figure>
                    <img src="{{ asset('storage/' . $absensi->foto) }}" alt="Foto absensi" class="h-56 w-full object-cover">
                </figure>
            @endif

            <div class="card-body space-y-4 p-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-muted">Lokasi Geofencing</p>
                    <p class="mt-1 font-bold text-base-content">{{ $absensi->lokasiData->nama_lokasi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-muted">Lokasi Pekerjaan</p>
                    <p class="mt-1 font-bold text-base-content">{{ $absensi->lokasi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-muted">Uraian Kegiatan</p>
                    <p class="mt-1 text-sm leading-relaxed text-base-content/80">{{ $absensi->uraian ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-muted">Checklist APD</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @forelse($absensi->checklist_apd ?? [] as $apd)
                            <span class="badge badge-outline badge-sm font-bold">{{ $apd }}</span>
                        @empty
                            <span class="text-sm text-base-content/40">Tidak ada APD</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Map card --}}
        <div class="k3l-card-static">
            <div class="card-body p-5">
                <h2 class="text-base font-extrabold text-base-content">Peta Lokasi</h2>
                <p class="text-xs text-muted">Titik absensi dan radius lokasi geofencing.</p>

                <div id="map" class="mt-4 min-h-[350px] rounded-2xl border border-base-300 bg-base-200 lg:min-h-[400px]"></div>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('absensi.index') }}" class="btn btn-outline btn-sm">Kembali</a>
                    @if($absensi->latitude && $absensi->longitude)
                        <a href="https://www.google.com/maps?q={{ $absensi->latitude }},{{ $absensi->longitude }}" target="_blank" class="btn btn-primary btn-sm">Buka Google Maps</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    const lat = {{ $absensi->latitude ? (float) $absensi->latitude : 'null' }};
    const lng = {{ $absensi->longitude ? (float) $absensi->longitude : 'null' }};
    const fenceLat = {{ $absensi->lokasiData?->latitude ? (float) $absensi->lokasiData->latitude : 'null' }};
    const fenceLng = {{ $absensi->lokasiData?->longitude ? (float) $absensi->lokasiData->longitude : 'null' }};
    const radius = {{ $absensi->lokasiData?->radius ? (int) $absensi->lokasiData->radius : 100 }};

    const center = lat && lng ? [lat, lng] : (fenceLat && fenceLng ? [fenceLat, fenceLng] : [-2.990934, 104.756554]);
    const map = L.map('map').setView(center, 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    if (fenceLat && fenceLng) {
        L.circle([fenceLat, fenceLng], {
            radius: radius, color: '#0891b2', fillColor: '#67e8f9', fillOpacity: 0.2
        }).addTo(map).bindPopup('Area Geofencing');
    }

    if (lat && lng) {
        L.marker([lat, lng]).addTo(map).bindPopup('Lokasi Absensi').openPopup();
    }
</script>

@endsection
