@extends('layouts.app-supervisor')

@section('content')

<div class="max-w-5xl space-y-5">

    <div>
        <a href="{{ route('lokasi.index') }}" class="btn btn-ghost btn-xs gap-1 text-muted">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>
        <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-base-content">Edit Lokasi</h1>
        <p class="mt-1 text-sm text-muted">Update area polygon geofencing</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error text-sm font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="k3l-card-static">
        <div class="p-5 lg:p-7">
            <form action="{{ route('lokasi.update', $lokasi) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-xs font-bold">Nama Lokasi</span></label>
                    <input type="text" name="nama_lokasi" id="namaLokasi" value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}" class="input input-bordered input-sm w-full" required>
                </div>

                {{-- Search --}}
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-xs font-bold">Cari Batas Wilayah</span></label>
                    <p class="mb-2 text-xs text-muted">Ketik nama wilayah untuk mengambil batas wilayah otomatis, atau edit polygon yang sudah ada di peta.</p>
                    <div class="join w-full">
                        <input type="text" id="searchInput" class="input input-bordered input-sm join-item flex-1" placeholder="Contoh: Ilir Barat 1, Palembang">
                        <button type="button" id="btnSearch" class="btn btn-primary btn-sm join-item">Cari</button>
                    </div>
                    <div id="searchResults" class="mt-2 space-y-1"></div>
                </div>

                {{-- Map --}}
                <div>
                    <label class="label py-1"><span class="label-text text-xs font-bold">Peta Geofencing</span></label>
                    <div id="map" style="width: 100%; height: 400px; border-radius: 1rem; border: 1px solid oklch(0.869 0.022 252.894); z-index: 1;"></div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Latitude (Pusat)</span></label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $lokasi->latitude) }}" class="input input-bordered input-sm bg-base-200" readonly>
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Longitude (Pusat)</span></label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $lokasi->longitude) }}" class="input input-bordered input-sm bg-base-200" readonly>
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Radius Fallback (m)</span></label>
                        <input type="number" name="radius" value="{{ old('radius', $lokasi->radius) }}" class="input input-bordered input-sm w-full" placeholder="100">
                    </div>
                </div>

                <input type="hidden" name="polygon" id="polygonInput" value="{{ old('polygon', json_encode($lokasi->polygon)) }}">

                <div id="polygonInfo" class="alert alert-success text-sm font-semibold hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Polygon aktif dengan <strong id="pointCount">0</strong> titik.</span>
                </div>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('lokasi.index') }}" class="btn btn-outline btn-sm">Kembali</a>
                    <button type="submit" class="btn btn-primary btn-sm gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg>
                        Update Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const existingPolygon = @json($lokasi->polygon);
    const centerLat = {{ $lokasi->latitude }};
    const centerLng = {{ $lokasi->longitude }};

    const map = L.map('map').setView([centerLat, centerLng], 15);
    let searchMarker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        draw: {
            polygon: { allowIntersection: false, shapeOptions: { color: '#0891b2', fillColor: '#67e8f9', fillOpacity: 0.3 } },
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
        const poly = L.polygon(latlngs, { color: '#0891b2', fillColor: '#67e8f9', fillOpacity: 0.3 });
        drawnItems.addLayer(poly);
        map.fitBounds(poly.getBounds());
        setPolygonData(latlngs);
    }

    map.on(L.Draw.Event.CREATED, function(e) { drawnItems.clearLayers(); drawnItems.addLayer(e.layer); setPolygonData(e.layer.getLatLngs()[0]); });
    map.on(L.Draw.Event.EDITED, function(e) { e.layers.eachLayer(function(layer) { setPolygonData(layer.getLatLngs()[0]); }); });
    map.on(L.Draw.Event.DELETED, function() { clearPolygon(); });

    const searchInput = document.getElementById('searchInput');
    const btnSearch = document.getElementById('btnSearch');
    const searchResults = document.getElementById('searchResults');

    async function searchBoundary() {
        const query = searchInput.value.trim();
        if (!query) return;
        searchResults.innerHTML = '<div class="text-xs font-semibold text-muted">Mencari batas wilayah...</div>';
        try {
            const params = new URLSearchParams({ format: 'json', limit: '10', polygon_geojson: '1', addressdetails: '1', q: query });
            const response = await fetch('https://nominatim.openstreetmap.org/search?' + params.toString(), { headers: { 'Accept-Language': 'id' } });
            const data = await response.json();
            if (!data.length) { searchResults.innerHTML = '<div class="text-xs font-semibold text-error">Wilayah tidak ditemukan.</div>'; return; }
            const results = data.map(item => {
                const hasPolygon = item.geojson && (item.geojson.type === 'Polygon' || item.geojson.type === 'MultiPolygon');
                const isAdmin = item.osm_type === 'relation' || item.class === 'boundary' || item.type === 'administrative';
                return { ...item, hasPolygon, isAdmin };
            });
            results.sort((a, b) => { if (a.isAdmin && !b.isAdmin) return -1; if (!a.isAdmin && b.isAdmin) return 1; if (a.hasPolygon && !b.hasPolygon) return -1; if (!a.hasPolygon && b.hasPolygon) return 1; return 0; });
            searchResults.innerHTML = results.map((item, idx) => {
                let badge = '';
                if (item.isAdmin) badge = '<span class="badge badge-primary badge-xs mr-1">Wilayah</span>';
                else if (item.hasPolygon) badge = '<span class="badge badge-success badge-xs mr-1">Area</span>';
                else badge = '<span class="badge badge-ghost badge-xs mr-1">Titik</span>';
                return `<button type="button" data-idx="${idx}" class="search-result-btn btn btn-ghost btn-xs justify-start w-full text-left text-[0.7rem] font-semibold">${badge} ${item.display_name}</button>`;
            }).join('');
            document.querySelectorAll('.search-result-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const item = results[parseInt(this.dataset.idx)];
                    searchResults.innerHTML = '<div class="text-xs font-semibold text-muted">Mengambil batas wilayah...</div>';
                    if (item.hasPolygon) { applyGeoJson(item.geojson, item.display_name); searchResults.innerHTML = '<div class="text-xs font-semibold text-success">Batas wilayah berhasil diterapkan.</div>'; }
                    else if (item.osm_type === 'relation') { const success = await fetchBoundaryFromOverpass(item.osm_id, item.display_name); if (!success) goToPoint(item); }
                    else if (item.osm_type === 'way') { const success = await fetchWayFromOverpass(item.osm_id, item.display_name); if (!success) goToPoint(item); }
                    else goToPoint(item);
                });
            });
        } catch (error) { searchResults.innerHTML = '<div class="text-xs font-semibold text-error">Gagal mencari. Periksa koneksi internet.</div>'; }
    }

    async function fetchBoundaryFromOverpass(osmId, displayName) {
        try {
            const query = `[out:json][timeout:25];relation(${osmId});out geom;`;
            const response = await fetch('https://overpass-api.de/api/interpreter', { method: 'POST', body: 'data=' + encodeURIComponent(query), headers: { 'Content-Type': 'application/x-www-form-urlencoded' } });
            const result = await response.json();
            if (!result.elements || !result.elements.length) return false;
            const relation = result.elements[0];
            const outerMembers = relation.members.filter(m => m.role === 'outer' && m.type === 'way');
            if (!outerMembers.length) return false;
            const coords = assemblePolygon(outerMembers);
            if (coords.length < 3) return false;
            loadPolygonOnMap(coords);
            const namaInput = document.getElementById('namaLokasi');
            if (!namaInput.value.trim()) namaInput.value = displayName.split(',')[0];
            searchResults.innerHTML = '<div class="text-xs font-semibold text-success">Batas wilayah berhasil diterapkan.</div>';
            return true;
        } catch (error) { return false; }
    }

    async function fetchWayFromOverpass(osmId, displayName) {
        try {
            const query = `[out:json][timeout:25];way(${osmId});out geom;`;
            const response = await fetch('https://overpass-api.de/api/interpreter', { method: 'POST', body: 'data=' + encodeURIComponent(query), headers: { 'Content-Type': 'application/x-www-form-urlencoded' } });
            const result = await response.json();
            if (!result.elements || !result.elements.length) return false;
            const way = result.elements[0];
            if (!way.geometry || way.geometry.length < 3) return false;
            const coords = way.geometry.map(p => [p.lat, p.lon]);
            const first = coords[0], last = coords[coords.length - 1];
            if (first[0] === last[0] && first[1] === last[1]) coords.pop();
            loadPolygonOnMap(coords);
            const namaInput = document.getElementById('namaLokasi');
            if (!namaInput.value.trim()) namaInput.value = displayName.split(',')[0];
            searchResults.innerHTML = '<div class="text-xs font-semibold text-success">Batas wilayah berhasil diterapkan.</div>';
            return true;
        } catch (error) { return false; }
    }

    function assemblePolygon(ways) {
        let segments = ways.map(w => w.geometry.map(p => [p.lat, p.lon]));
        if (!segments.length) return [];
        const result = segments.shift();
        let maxIterations = segments.length * 2;
        while (segments.length > 0 && maxIterations > 0) {
            maxIterations--;
            const lastPoint = result[result.length - 1];
            let found = false;
            for (let i = 0; i < segments.length; i++) {
                const seg = segments[i];
                if (lastPoint[0] === seg[0][0] && lastPoint[1] === seg[0][1]) { result.push(...seg.slice(1)); segments.splice(i, 1); found = true; break; }
                else if (lastPoint[0] === seg[seg.length-1][0] && lastPoint[1] === seg[seg.length-1][1]) { result.push(...seg.reverse().slice(1)); segments.splice(i, 1); found = true; break; }
            }
            if (!found) result.push(...segments.shift());
        }
        if (result.length > 1) { const first = result[0], last = result[result.length - 1]; if (first[0] === last[0] && first[1] === last[1]) result.pop(); }
        return result;
    }

    function applyGeoJson(geojson, displayName) {
        let coords = [];
        if (geojson.type === 'Polygon') coords = geojson.coordinates[0].map(c => [c[1], c[0]]);
        else if (geojson.type === 'MultiPolygon') {
            let largest = geojson.coordinates[0];
            geojson.coordinates.forEach(poly => { if (poly[0].length > largest[0].length) largest = poly; });
            coords = largest[0].map(c => [c[1], c[0]]);
        }
        if (coords.length > 0) {
            const first = coords[0], last = coords[coords.length - 1];
            if (first[0] === last[0] && first[1] === last[1]) coords.pop();
            loadPolygonOnMap(coords);
            const namaInput = document.getElementById('namaLokasi');
            if (!namaInput.value.trim()) namaInput.value = displayName.split(',')[0];
        }
    }

    function goToPoint(item) {
        const lat = parseFloat(item.lat); const lng = parseFloat(item.lon);
        if (searchMarker) map.removeLayer(searchMarker);
        searchMarker = L.marker([lat, lng]).addTo(map).bindPopup('<b>' + item.display_name.split(',')[0] + '</b><br><small>Gambar polygon manual di sekitar titik ini</small>').openPopup();
        map.setView([lat, lng], 16);
        const namaInput = document.getElementById('namaLokasi');
        if (!namaInput.value.trim()) namaInput.value = item.display_name.split(',')[0];
        searchResults.innerHTML = '<div class="text-xs font-semibold text-info">Peta dipindahkan ke lokasi. Gunakan tombol polygon di kiri atas peta untuk menggambar batas wilayah manual.</div>';
    }

    btnSearch.addEventListener('click', searchBoundary);
    searchInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); searchBoundary(); } });

    if (existingPolygon && existingPolygon.length >= 3) {
        loadPolygonOnMap(existingPolygon);
    }

    setTimeout(function() { map.invalidateSize(); }, 200);
});
</script>

@endsection
