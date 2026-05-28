@extends('layouts.app-petugas')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

<div class="mx-auto max-w-6xl space-y-5">
    <div>
        <p class="eyebrow">Absensi Geofencing</p>
        <h1 class="mt-1 text-[1.65rem] font-extrabold tracking-tight">Input Absensi</h1>
        <p class="mt-1 text-sm text-muted">Pastikan GPS aktif dan Anda berada di dalam area lokasi kerja.</p>
    </div>

    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @if (session('error'))
        <x-alert type="error" :message="session('error')" />
    @endif

    @if ($errors->any())
        <x-alert type="error">Ada data yang belum sesuai. Periksa kembali form absensi.</x-alert>
    @endif

    <form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Basic info --}}
        <div class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Tanggal Absensi</span></label>
                        <input type="text" value="{{ $tanggalHariIni }}" class="input input-bordered input-sm bg-base-200" readonly>
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Jam Absensi</span></label>
                        <input type="text" value="{{ $jamSekarang }}" class="input input-bordered input-sm bg-base-200" readonly>
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Status</span></label>
                        <select name="status" id="status" class="select select-bordered select-sm w-full" required>
                            <option value="standby" {{ old('status') === 'standby' ? 'selected' : '' }}>Standby</option>
                            <option value="progress" {{ old('status') === 'progress' ? 'selected' : '' }}>Progress</option>
                        </select>
                        @error('status') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Geofencing --}}
        <div class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_1.4fr]">
                    <div class="space-y-4">
                        <div class="form-control">
                            <label class="label py-1"><span class="label-text text-xs font-bold">Lokasi Geofencing</span></label>
                            <select name="lokasi_id" id="lokasiSelect" class="select select-bordered select-sm w-full" required>
                                <option value="">-- Pilih lokasi resmi --</option>
                                @foreach($lokasiList as $lokasi)
                                    <option
                                        value="{{ $lokasi->id }}"
                                        data-name="{{ $lokasi->nama_lokasi }}"
                                        data-lat="{{ $lokasi->latitude }}"
                                        data-lng="{{ $lokasi->longitude }}"
                                        data-radius="{{ $lokasi->radius }}"
                                        data-polygon="{{ json_encode($lokasi->polygon) }}"
                                        {{ old('lokasi_id') == $lokasi->id ? 'selected' : '' }}
                                    >
                                        {{ $lokasi->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi_id') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label py-1"><span class="label-text text-xs font-bold">Cari di Maps</span></label>
                            <div class="join w-full">
                                <input id="mapSearch" type="text" class="input input-bordered input-sm join-item flex-1" placeholder="Cari alamat atau nama tempat">
                                <button type="button" id="btnSearchMap" class="btn btn-neutral btn-sm join-item">Cari</button>
                            </div>
                            <div id="searchResults" class="mt-2 space-y-1"></div>
                        </div>

                        <x-alert type="info">Validasi memakai GPS Anda dan batas area polygon lokasi geofencing yang dipilih.</x-alert>

                        <div id="statusArea">
                            <x-alert type="warning">Menunggu akses GPS. Izinkan lokasi agar tombol absen aktif.</x-alert>
                        </div>

                        <button type="button" id="btnRefreshGps" class="btn btn-outline btn-sm w-full gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            Ambil Ulang Lokasi GPS
                        </button>
                    </div>

                    <div id="map" class="min-h-[350px] rounded-2xl border border-base-300 bg-base-200 lg:min-h-[420px]"></div>
                </div>
            </div>
        </div>

        {{-- Lokasi Pekerjaan --}}
        <div class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-xs font-bold">Lokasi Pekerjaan</span></label>
                    <input type="text" name="lokasi" id="lokasiKerja" value="{{ old('lokasi') }}" class="input input-bordered input-sm w-full" placeholder="Contoh: Area panel listrik lantai 2">
                    @error('lokasi') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Uraian --}}
        <div class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-xs font-bold">Uraian Kegiatan</span></label>
                    <textarea name="uraian" rows="3" class="textarea textarea-bordered w-full text-sm" placeholder="Tuliskan kegiatan atau kondisi standby..." required>{{ old('uraian') }}</textarea>
                    @error('uraian') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- APD Checklist --}}
        <div id="progressBox" class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <h2 class="text-base font-extrabold text-base-content">Checklist APD</h2>
                <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($apdItems as $item)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-base-300 px-4 py-3 text-sm font-semibold transition hover:bg-base-200">
                            <input type="checkbox" name="checklist_apd[]" value="{{ $item }}" class="checkbox checkbox-primary checkbox-sm" {{ in_array($item, old('checklist_apd', []), true) ? 'checked' : '' }}>
                            {{ $item }}
                        </label>
                    @endforeach
                </div>
                @error('checklist_apd') <p class="mt-2 text-xs font-semibold text-error">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Foto --}}
        <div class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-xs font-bold">Foto Bukti</span></label>
                    <input type="file" name="foto" accept="image/*" capture="environment" class="file-input file-input-bordered file-input-sm w-full" required>
                    <div class="mt-3">
                        <img id="previewFoto" class="hidden w-full max-h-[300px] rounded-2xl border border-base-300 object-cover">
                    </div>
                    <p class="mt-2 text-xs text-base-content/50">Wajib untuk Standby dan Progress, maksimal 2 MB.</p>
                    @error('foto') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

        {{-- Actions --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('absensi.index') }}" class="btn btn-outline btn-sm">Kembali</a>
            <button id="btnSubmit" disabled class="btn btn-primary btn-sm">Absen Sekarang</button>
        </div>
    </form>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    const lokasiSelect = document.getElementById('lokasiSelect');
    const statusArea = document.getElementById('statusArea');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnRefreshGps = document.getElementById('btnRefreshGps');
    const statusInput = document.getElementById('status');
    const progressBox = document.getElementById('progressBox');
    const mapSearch = document.getElementById('mapSearch');
    const btnSearchMap = document.getElementById('btnSearchMap');
    const searchResults = document.getElementById('searchResults');
    const lokasiKerja = document.getElementById('lokasiKerja');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const fotoInput = document.querySelector('input[name="foto"]');
    const previewFoto = document.getElementById('previewFoto');

    let map = L.map('map').setView([-2.990934, 104.756554], 13);
    let userMarker;
    let searchMarker;
    let polygonLayer;
    let userLat = parseFloat(latitudeInput.value) || null;
    let userLng = parseFloat(longitudeInput.value) || null;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    function alertHtml(type, text) {
        const icons = {
            success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
            error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
            warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
            info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
        };
        const classes = { success: 'alert-success', error: 'alert-error', warning: 'alert-warning', info: 'alert-info' };
        return `<div class="alert ${classes[type]} text-sm font-semibold" role="alert"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">${icons[type]}</svg><span>${text}</span></div>`;
    }

    function syncProgressBox() {
        progressBox.style.display = statusInput.value === 'progress' ? 'block' : 'none';
    }

    function selectedLokasi() {
        const option = lokasiSelect.options[lokasiSelect.selectedIndex];
        if (!option || !option.value) return null;
        let polygon = null;
        try { polygon = JSON.parse(option.dataset.polygon); } catch (e) {}
        return {
            id: option.value,
            name: option.dataset.name,
            lat: parseFloat(option.dataset.lat),
            lng: parseFloat(option.dataset.lng),
            radius: parseFloat(option.dataset.radius),
            polygon: polygon
        };
    }

    function pointInPolygon(lat, lng, polygon) {
        if (!polygon || polygon.length < 3) return false;
        let inside = false;
        const n = polygon.length;
        for (let i = 0, j = n - 1; i < n; j = i++) {
            const xi = polygon[i][0], yi = polygon[i][1];
            const xj = polygon[j][0], yj = polygon[j][1];
            const intersect = ((yi > lng) !== (yj > lng)) && (lat < (xj - xi) * (lng - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }
        return inside;
    }

    function drawSelectedLokasi() {
        const lokasi = selectedLokasi();
        if (polygonLayer) map.removeLayer(polygonLayer);
        if (!lokasi) { validateGeofence(); return; }
        if (lokasi.polygon && lokasi.polygon.length >= 3) {
            const latlngs = lokasi.polygon.map(p => [p[0], p[1]]);
            polygonLayer = L.polygon(latlngs, { color: '#0891b2', fillColor: '#67e8f9', fillOpacity: 0.2 }).addTo(map).bindPopup(lokasi.name);
            map.fitBounds(polygonLayer.getBounds());
        } else {
            polygonLayer = L.circle([lokasi.lat, lokasi.lng], { radius: lokasi.radius, color: '#0891b2', fillColor: '#67e8f9', fillOpacity: 0.2 }).addTo(map);
            map.setView([lokasi.lat, lokasi.lng], 16);
        }
        validateGeofence();
    }

    function validateGeofence() {
        const lokasi = selectedLokasi();
        btnSubmit.disabled = true;
        if (!lokasi) { statusArea.innerHTML = alertHtml('warning', 'Pilih lokasi geofencing terlebih dahulu.'); return; }
        if (!userLat || !userLng) { statusArea.innerHTML = alertHtml('warning', 'Menunggu GPS. Izinkan akses lokasi dan tekan Ambil Ulang Lokasi GPS bila perlu.'); return; }
        let isInside = false;
        if (lokasi.polygon && lokasi.polygon.length >= 3) {
            isInside = pointInPolygon(userLat, userLng, lokasi.polygon);
        } else {
            const distance = map.distance([userLat, userLng], [lokasi.lat, lokasi.lng]);
            isInside = distance <= lokasi.radius;
        }
        if (isInside) {
            statusArea.innerHTML = alertHtml('success', 'Anda berada di dalam area geofencing. Absensi dapat dilakukan.');
            btnSubmit.disabled = false;
        } else {
            statusArea.innerHTML = alertHtml('error', 'Anda berada di luar area geofencing. Pastikan Anda berada di dalam batas wilayah yang ditentukan.');
        }
    }

    function getGps() {
        statusArea.innerHTML = alertHtml('info', 'Mengambil lokasi GPS...');
        if (!navigator.geolocation) { statusArea.innerHTML = alertHtml('error', 'Browser tidak mendukung geolocation.'); return; }
        navigator.geolocation.getCurrentPosition(
            function(position) {
                userLat = position.coords.latitude;
                userLng = position.coords.longitude;
                latitudeInput.value = userLat;
                longitudeInput.value = userLng;
                if (userMarker) map.removeLayer(userMarker);
                userMarker = L.marker([userLat, userLng]).addTo(map).bindPopup('Lokasi Anda');
                map.setView([userLat, userLng], 16);
                validateGeofence();
            },
            function(error) {
                statusArea.innerHTML = alertHtml('error', 'GPS tidak diizinkan atau lokasi gagal didapatkan. Aktifkan GPS lalu coba lagi.');
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    async function searchMap() {
        const query = mapSearch.value.trim();
        if (!query) return;
        searchResults.innerHTML = '<div class="text-xs font-semibold text-base-content/50">Mencari lokasi...</div>';
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=5&q=${encodeURIComponent(query)}`);
            const data = await response.json();
            if (!data.length) { searchResults.innerHTML = '<div class="text-xs font-semibold text-error">Lokasi tidak ditemukan.</div>'; return; }
            searchResults.innerHTML = data.map((item, index) => `
                <button type="button" data-index="${index}" class="map-result btn btn-ghost btn-xs justify-start w-full text-left text-[0.7rem] font-semibold">${item.display_name}</button>
            `).join('');
            document.querySelectorAll('.map-result').forEach(button => {
                button.addEventListener('click', function() {
                    const item = data[this.dataset.index];
                    const lat = parseFloat(item.lat);
                    const lng = parseFloat(item.lon);
                    if (searchMarker) map.removeLayer(searchMarker);
                    searchMarker = L.marker([lat, lng]).addTo(map).bindPopup(item.display_name).openPopup();
                    map.setView([lat, lng], 16);
                    lokasiKerja.value = item.display_name;
                });
            });
        } catch (error) {
            searchResults.innerHTML = '<div class="text-xs font-semibold text-error">Pencarian maps gagal. Periksa koneksi internet.</div>';
        }
    }

    statusInput.addEventListener('change', syncProgressBox);
    lokasiSelect.addEventListener('change', drawSelectedLokasi);
    btnRefreshGps.addEventListener('click', getGps);
    btnSearchMap.addEventListener('click', searchMap);
    mapSearch.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') { event.preventDefault(); searchMap(); }
    });

    syncProgressBox();
    drawSelectedLokasi();
    getGps();

    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) { previewFoto.src = URL.createObjectURL(file); previewFoto.classList.remove('hidden'); }
    });
</script>
@endsection
