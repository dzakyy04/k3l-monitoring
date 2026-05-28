@php $pageTitle = 'Tambah Lokasi'; $pageSubtitle = 'Buat polygon area geofence'; @endphp
@extends('layouts.app-supervisor')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css">
@endpush

@section('content')

<section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <p class="eyebrow">Geofence Baru</p>
        <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">Tambah Lokasi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Cari wilayah otomatis atau gambar polygon manual di peta.</p>
    </div>
    <a href="{{ route('lokasi.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors self-start">
        <x-icon name="arrow-left" class="w-4 h-4" />
        Kembali
    </a>
</section>

@if($errors->any())
    <x-alert type="error">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-alert>
@endif

<form action="{{ route('lokasi.store') }}" method="POST" id="lokasiForm" class="space-y-5">
    @csrf

    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Identitas Lokasi</h3>
        <div class="mt-4">
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Nama Lokasi</label>
            <input type="text" name="nama_lokasi" id="namaLokasi" value="{{ old('nama_lokasi') }}" required placeholder="Contoh: Kantor PLN Pusat"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
        </div>
    </article>

    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Cari Batas Wilayah</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Ketik nama wilayah (kecamatan / kelurahan / kota) lalu klik Cari.</p>

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
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Peta Geofence</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Polygon dapat diisi otomatis dari pencarian atau digambar manual.</p>

        <div id="map" class="mt-4 w-full rounded-2xl border border-slate-200 dark:border-white/10" style="height:420px; z-index:1;"></div>

        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Latitude (Pusat)</label>
                <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" readonly
                       class="mt-1 w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl font-mono-data text-slate-700 dark:text-slate-300">
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Longitude (Pusat)</label>
                <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" readonly
                       class="mt-1 w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl font-mono-data text-slate-700 dark:text-slate-300">
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Radius Fallback (m)</label>
                <input type="number" name="radius" value="{{ old('radius', 100) }}" placeholder="100"
                       class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
            </div>
        </div>

        <input type="hidden" name="polygon" id="polygonInput" value="{{ old('polygon') }}">

        <div id="polygonInfo" class="hidden mt-4">
            <x-alert type="success">Polygon aktif dengan <strong id="pointCount">0</strong> titik.</x-alert>
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
            Simpan Lokasi
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let map = L.map('map').setView([-2.990934, 104.756554], 13);
    let searchMarker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        draw: {
            polygon: { allowIntersection: false, shapeOptions: { color: '#0284C7', fillColor: '#7DD3FC', fillOpacity: 0.3 } },
            polyline: false, circle: false, rectangle: false, marker: false, circlemarker: false
        },
        edit: { featureGroup: drawnItems, remove: true }
    });
    map.addControl(drawControl);

    function setPolygonData(latlngs) {
        const polygon = latlngs.map(ll => [ll.lat !== undefined ? ll.lat : ll[0], ll.lng !== undefined ? ll.lng : ll[1]]);
        document.getElementById('polygonInput').value = JSON.stringify(polygon);
        let latSum = 0, lngSum = 0;
        polygon.forEach(p => { latSum += p[0]; lngSum += p[1]; });
        document.getElementById('latitude').value = (latSum / polygon.length).toFixed(7);
        document.getElementById('longitude').value = (lngSum / polygon.length).toFixed(7);
        document.getElementById('polygonInfo').classList.remove('hidden');
        document.getElementById('pointCount').textContent = polygon.length;
    }

    function clearPolygon() {
        drawnItems.clearLayers();
        document.getElementById('polygonInput').value = '';
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('polygonInfo').classList.add('hidden');
    }

    function loadPolygonOnMap(coords) {
        drawnItems.clearLayers();
        const latlngs = coords.map(c => L.latLng(c[0], c[1]));
        const poly = L.polygon(latlngs, { color: '#0284C7', fillColor: '#7DD3FC', fillOpacity: 0.3 });
        drawnItems.addLayer(poly);
        map.fitBounds(poly.getBounds());
        setPolygonData(latlngs);
    }

    map.on(L.Draw.Event.CREATED, e => { drawnItems.clearLayers(); drawnItems.addLayer(e.layer); setPolygonData(e.layer.getLatLngs()[0]); });
    map.on(L.Draw.Event.EDITED, e => { e.layers.eachLayer(layer => setPolygonData(layer.getLatLngs()[0])); });
    map.on(L.Draw.Event.DELETED, () => clearPolygon());

    const searchInput = document.getElementById('searchInput');
    const btnSearch = document.getElementById('btnSearch');
    const searchResults = document.getElementById('searchResults');

    function badgePill(type, label) {
        const map = {
            wilayah: 'pill pill-info',
            area: 'pill pill-success',
            titik: 'pill pill-muted',
        };
        return `<span class="${map[type]}"><span class="dot"></span>${label}</span>`;
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
                btn.addEventListener('click', async function() {
                    const item = results[parseInt(this.dataset.idx)];
                    searchResults.innerHTML = '<div class="text-xs font-semibold text-slate-500 dark:text-slate-400 dark:text-slate-500">Mengambil batas wilayah...</div>';
                    if (item.hasPolygon) { applyGeoJson(item.geojson, item.display_name); searchResults.innerHTML = '<div class="text-xs font-semibold text-emerald-700 dark:text-emerald-300">Batas wilayah diterapkan.</div>'; }
                    else if (item.osm_type === 'relation') { const ok = await fetchBoundaryFromOverpass(item.osm_id, item.display_name); if (!ok) goToPoint(item); }
                    else if (item.osm_type === 'way') { const ok = await fetchWayFromOverpass(item.osm_id, item.display_name); if (!ok) goToPoint(item); }
                    else goToPoint(item);
                });
            });
        } catch (e) { searchResults.innerHTML = '<div class="text-xs font-semibold text-red-600 dark:text-red-400">Gagal mencari. Periksa koneksi.</div>'; }
    }

    async function fetchBoundaryFromOverpass(osmId, displayName) {
        try {
            const q = `[out:json][timeout:25];relation(${osmId});out geom;`;
            const r = await fetch('https://overpass-api.de/api/interpreter', { method: 'POST', body: 'data=' + encodeURIComponent(q), headers: { 'Content-Type': 'application/x-www-form-urlencoded' } });
            const result = await r.json();
            if (!result.elements?.length) return false;
            const relation = result.elements[0];
            const outerMembers = relation.members.filter(m => m.role === 'outer' && m.type === 'way');
            if (!outerMembers.length) return false;
            const coords = assemblePolygon(outerMembers);
            if (coords.length < 3) return false;
            loadPolygonOnMap(coords);
            const namaInput = document.getElementById('namaLokasi');
            if (!namaInput.value.trim()) namaInput.value = displayName.split(',')[0];
            searchResults.innerHTML = '<div class="text-xs font-semibold text-emerald-700 dark:text-emerald-300">Batas wilayah diterapkan.</div>';
            return true;
        } catch (e) { return false; }
    }

    async function fetchWayFromOverpass(osmId, displayName) {
        try {
            const q = `[out:json][timeout:25];way(${osmId});out geom;`;
            const r = await fetch('https://overpass-api.de/api/interpreter', { method: 'POST', body: 'data=' + encodeURIComponent(q), headers: { 'Content-Type': 'application/x-www-form-urlencoded' } });
            const result = await r.json();
            if (!result.elements?.length) return false;
            const way = result.elements[0];
            if (!way.geometry || way.geometry.length < 3) return false;
            const coords = way.geometry.map(p => [p.lat, p.lon]);
            const f = coords[0], l = coords[coords.length - 1];
            if (f[0] === l[0] && f[1] === l[1]) coords.pop();
            loadPolygonOnMap(coords);
            const namaInput = document.getElementById('namaLokasi');
            if (!namaInput.value.trim()) namaInput.value = displayName.split(',')[0];
            searchResults.innerHTML = '<div class="text-xs font-semibold text-emerald-700 dark:text-emerald-300">Batas wilayah diterapkan.</div>';
            return true;
        } catch (e) { return false; }
    }

    function assemblePolygon(ways) {
        let segments = ways.map(w => w.geometry.map(p => [p.lat, p.lon]));
        if (!segments.length) return [];
        const result = segments.shift();
        let max = segments.length * 2;
        while (segments.length && max > 0) {
            max--;
            const last = result[result.length - 1];
            let found = false;
            for (let i = 0; i < segments.length; i++) {
                const seg = segments[i];
                if (last[0] === seg[0][0] && last[1] === seg[0][1]) { result.push(...seg.slice(1)); segments.splice(i, 1); found = true; break; }
                if (last[0] === seg[seg.length-1][0] && last[1] === seg[seg.length-1][1]) { result.push(...seg.reverse().slice(1)); segments.splice(i, 1); found = true; break; }
            }
            if (!found) result.push(...segments.shift());
        }
        if (result.length > 1) {
            const f = result[0], l = result[result.length - 1];
            if (f[0] === l[0] && f[1] === l[1]) result.pop();
        }
        return result;
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
        if (searchMarker) map.removeLayer(searchMarker);
        searchMarker = L.marker([lat, lng]).addTo(map).bindPopup('<b>' + item.display_name.split(',')[0] + '</b>').openPopup();
        map.setView([lat, lng], 16);
        const namaInput = document.getElementById('namaLokasi');
        if (!namaInput.value.trim()) namaInput.value = item.display_name.split(',')[0];
        searchResults.innerHTML = '<div class="text-xs font-semibold text-sky-700">Peta dipindahkan. Gambar polygon manual via toolbar peta.</div>';
    }

    btnSearch.addEventListener('click', searchBoundary);
    searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); searchBoundary(); } });

    const oldPolygon = document.getElementById('polygonInput').value;
    if (oldPolygon) { try { const coords = JSON.parse(oldPolygon); if (coords.length >= 3) loadPolygonOnMap(coords); } catch (e) {} }
    setTimeout(() => map.invalidateSize(), 200);
});
</script>
@endpush
