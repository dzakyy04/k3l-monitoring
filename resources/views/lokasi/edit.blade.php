@php $pageTitle = 'Edit Lokasi'; $pageSubtitle = 'Update polygon area kerja'; @endphp
@extends('layouts.app-supervisor')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css">
@endpush

@section('content')

<section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <p class="eyebrow">Update Area Kerja</p>
        <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">Edit Lokasi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Sesuaikan polygon area {{ $lokasi->nama_lokasi }}.</p>
    </div>
    <a href="{{ route('lokasi.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors self-start">
        <x-icon name="arrow-left" class="w-4 h-4" />
        Kembali
    </a>
</section>

@if($errors->any())
    @push('scripts')
    <script>Swal.fire({ icon: 'error', title: 'Oops!', text: @js(implode(', ', $errors->all())), confirmButtonColor: '#0284C7' });</script>
    @endpush
@endif

<form action="{{ route('lokasi.update', $lokasi) }}" method="POST" class="space-y-5" data-submit-text="Menyimpan perubahan...">
    @csrf @method('PUT')

    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Identitas Lokasi</h3>
        <div class="mt-4">
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Nama Lokasi</label>
            <input type="text" name="nama_lokasi" id="namaLokasi" value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}" required
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
        </div>
    </article>

    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Cari Batas Wilayah</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Cari ulang untuk mengganti polygon, atau edit polygon di peta.</p>

        <div class="mt-4 flex gap-2">
            <input type="text" id="searchInput" placeholder="Contoh: Ilir Barat 1, Palembang"
                   class="flex-1 px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
            <button type="button" id="btnSearch"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl cursor-pointer focus-ring">
                <x-icon name="search" class="w-4 h-4" />Cari
            </button>
        </div>
        <div id="searchResults" class="mt-3 space-y-1"></div>
    </article>

    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Peta Area Kerja</h3>

        <div id="map" class="mt-4 w-full rounded-2xl border border-slate-200 dark:border-white/10" style="height:420px; z-index:1;"></div>

        <div id="coordinateWrapper" class="hidden">
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Latitude (Pusat)</label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $lokasi->latitude) }}" readonly
                           class="mt-1 w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl font-mono-data text-slate-700 dark:text-slate-300">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Longitude (Pusat)</label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $lokasi->longitude) }}" readonly
                           class="mt-1 w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl font-mono-data text-slate-700 dark:text-slate-300">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Radius (Meter)</label>
                    <input type="number" name="radius" id="radiusInput" value="{{ old('radius', $lokasi->radius) }}" min="1"
                           class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                </div>
            </div>
        </div>

        <input type="hidden" name="polygon" id="polygonInput" value="{{ old('polygon', json_encode($lokasi->polygon)) }}">

        <div id="polygonInfo" class="hidden mt-4">
            <x-alert type="success">Polygon aktif dengan <strong id="pointCount">0</strong> titik.</x-alert>
        </div>
        <div id="markerInfo" class="hidden mt-4">
            <x-alert type="info">Titik pusat aktif. Silakan tentukan radius pada kolom di atas.</x-alert>
        </div>
    </article>

    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
        <a href="{{ route('lokasi.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 rounded-full cursor-pointer focus-ring transition-colors">
            Batal
        </a>
        <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
            <x-icon name="save" class="w-4 h-4" />
            Update Lokasi
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const existingPolygon = @json($lokasi->polygon);
    const centerLat = {{ $lokasi->latitude }};
    const centerLng = {{ $lokasi->longitude }};

    const map = L.map('map').setView([centerLat, centerLng], 15);
    let searchMarker;
    let markerCircle;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        draw: {
            polygon: { allowIntersection: false, shapeOptions: { color: '#0284C7', fillColor: '#7DD3FC', fillOpacity: 0.3 } },
            marker: true,
            polyline: false, circle: false, rectangle: false, circlemarker: false
        },
        edit: { featureGroup: drawnItems, remove: true }
    });
    map.addControl(drawControl);

    function setPolygonData(latlngs) {
        if (markerCircle) map.removeLayer(markerCircle);
        const polygon = latlngs.map(ll => [ll.lat !== undefined ? ll.lat : ll[0], ll.lng !== undefined ? ll.lng : ll[1]]);
        document.getElementById('polygonInput').value = JSON.stringify(polygon);
        let latSum = 0, lngSum = 0;
        polygon.forEach(p => { latSum += p[0]; lngSum += p[1]; });
        document.getElementById('latitude').value = (latSum / polygon.length).toFixed(7);
        document.getElementById('longitude').value = (lngSum / polygon.length).toFixed(7);
        document.getElementById('polygonInfo').classList.remove('hidden');
        document.getElementById('markerInfo').classList.add('hidden');
        document.getElementById('coordinateWrapper').classList.add('hidden');
        document.getElementById('pointCount').textContent = polygon.length;
    }

    function drawMarkerCircle(latlng) {
        if (markerCircle) map.removeLayer(markerCircle);
        const radius = parseFloat(document.getElementById('radiusInput').value) || 100;
        markerCircle = L.circle(latlng, { radius: radius, color: '#0284C7', fillColor: '#7DD3FC', fillOpacity: 0.2 }).addTo(map);
    }

    function setMarkerData(latlng) {
        document.getElementById('polygonInput').value = '[]';
        document.getElementById('latitude').value = latlng.lat.toFixed(7);
        document.getElementById('longitude').value = latlng.lng.toFixed(7);
        document.getElementById('polygonInfo').classList.add('hidden');
        document.getElementById('markerInfo').classList.remove('hidden');
        document.getElementById('coordinateWrapper').classList.remove('hidden');
        drawMarkerCircle(latlng);
    }

    document.getElementById('radiusInput').addEventListener('input', function() {
        if (markerCircle) markerCircle.setRadius(parseFloat(this.value) || 0);
    });

    function clearPolygon() {
        drawnItems.clearLayers();
        if (markerCircle) map.removeLayer(markerCircle);
        document.getElementById('polygonInput').value = '';
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('polygonInfo').classList.add('hidden');
        document.getElementById('markerInfo').classList.add('hidden');
        document.getElementById('coordinateWrapper').classList.add('hidden');
    }

    function loadPolygonOnMap(coords) {
        drawnItems.clearLayers();
        const latlngs = coords.map(c => L.latLng(c[0], c[1]));
        const poly = L.polygon(latlngs, { color: '#0284C7', fillColor: '#7DD3FC', fillOpacity: 0.3 });
        drawnItems.addLayer(poly);
        map.fitBounds(poly.getBounds());
        setPolygonData(latlngs);
    }

    map.on(L.Draw.Event.CREATED, e => { 
        drawnItems.clearLayers(); 
        drawnItems.addLayer(e.layer); 
        if (e.layerType === 'polygon') setPolygonData(e.layer.getLatLngs()[0]); 
        else if (e.layerType === 'marker') setMarkerData(e.layer.getLatLng());
    });
    
    map.on(L.Draw.Event.EDITED, e => { 
        e.layers.eachLayer(layer => {
            if (layer instanceof L.Polygon) setPolygonData(layer.getLatLngs()[0]);
            else if (layer instanceof L.Marker) setMarkerData(layer.getLatLng());
        }); 
    });
    
    map.on(L.Draw.Event.DELETED, () => clearPolygon());

    const searchInput = document.getElementById('searchInput');
    const btnSearch = document.getElementById('btnSearch');
    const searchResults = document.getElementById('searchResults');

    function badgePill(type, label) {
        const m = { wilayah: 'pill pill-info', area: 'pill pill-success', titik: 'pill pill-muted' };
        return `<span class="${m[type]}"><span class="dot"></span>${label}</span>`;
    }

    async function searchBoundary() {
        const query = searchInput.value.trim();
        if (!query) return;
        searchResults.innerHTML = '<div class="text-xs font-semibold text-slate-500 dark:text-slate-400 dark:text-slate-500">Mencari batas wilayah...</div>';
        try {
            const params = new URLSearchParams({ format: 'json', limit: '10', polygon_geojson: '1', addressdetails: '1', q: query });
            const r = await fetch('https://nominatim.openstreetmap.org/search?' + params.toString(), { headers: { 'Accept-Language': 'id' } });
            const data = await r.json();
            if (!data.length) { searchResults.innerHTML = '<div class="text-xs font-semibold text-red-600 dark:text-red-400">Wilayah tidak ditemukan.</div>'; return; }
            const results = data.map(item => {
                const hasPolygon = item.geojson && (item.geojson.type === 'Polygon' || item.geojson.type === 'MultiPolygon');
                const isAdmin = item.osm_type === 'relation' || item.class === 'boundary' || item.type === 'administrative';
                return { ...item, hasPolygon, isAdmin };
            });
            results.sort((a, b) => {
                if (a.isAdmin && !b.isAdmin) return -1;
                if (!a.isAdmin && b.isAdmin) return 1;
                if (a.hasPolygon && !b.hasPolygon) return -1;
                if (!a.hasPolygon && b.hasPolygon) return 1;
                return 0;
            });
            searchResults.innerHTML = results.map((item, idx) => {
                const badge = item.isAdmin ? badgePill('wilayah', 'Wilayah') : item.hasPolygon ? badgePill('area', 'Area') : badgePill('titik', 'Titik');
                return `<button type="button" data-idx="${idx}" class="search-result-btn flex items-center gap-2 w-full text-left text-[11px] font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60 px-3 py-2 rounded-lg cursor-pointer focus-ring">${badge}<span class="truncate">${item.display_name}</span></button>`;
            }).join('');
            document.querySelectorAll('.search-result-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const item = results[parseInt(this.dataset.idx)];
                    if (item.hasPolygon) {
                        applyGeoJson(item.geojson, item.display_name);
                        searchResults.innerHTML = '<div class="text-xs font-semibold text-emerald-700 dark:text-emerald-300">Batas wilayah diterapkan.</div>';
                    } else {
                        goToPoint(item);
                    }
                });
            });
        } catch (e) { searchResults.innerHTML = '<div class="text-xs font-semibold text-red-600 dark:text-red-400">Gagal mencari. Periksa koneksi.</div>'; }
    }

    function applyGeoJson(geojson, displayName) {
        let coords = [];
        if (geojson.type === 'Polygon') coords = geojson.coordinates[0].map(c => [c[1], c[0]]);
        else if (geojson.type === 'MultiPolygon') {
            let largest = geojson.coordinates[0];
            geojson.coordinates.forEach(p => { if (p[0].length > largest[0].length) largest = p; });
            coords = largest[0].map(c => [c[1], c[0]]);
        }
        if (coords.length) {
            const f = coords[0], l = coords[coords.length - 1];
            if (f[0] === l[0] && f[1] === l[1]) coords.pop();
            loadPolygonOnMap(coords);
            const namaInput = document.getElementById('namaLokasi');
            if (!namaInput.value.trim()) namaInput.value = displayName.split(',')[0];
        }
    }

    function goToPoint(item) {
        const lat = parseFloat(item.lat), lng = parseFloat(item.lon);
        drawnItems.clearLayers();
        const marker = L.marker([lat, lng]);
        drawnItems.addLayer(marker);
        setMarkerData(marker.getLatLng());
        map.setView([lat, lng], 16);
        searchResults.innerHTML = '<div class="text-xs font-semibold text-sky-700">Peta dipindahkan. Area radius 1 titik aktif.</div>';
    }

    btnSearch.addEventListener('click', searchBoundary);
    searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); searchBoundary(); } });

    if (existingPolygon && existingPolygon.length >= 3) {
        loadPolygonOnMap(existingPolygon);
    } else {
        const marker = L.marker([centerLat, centerLng]);
        drawnItems.addLayer(marker);
        setMarkerData(marker.getLatLng());
    }
    setTimeout(() => map.invalidateSize(), 200);
});
</script>
@endpush
