@php $pageTitle = 'Input Absensi'; $pageSubtitle = 'Isi form absensi geofencing'; @endphp
@extends('layouts.app-petugas')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
@endpush

@section('content')

{{-- Title row --}}
<section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <p class="eyebrow">Absensi Geofencing</p>
        <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">Input Absensi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Pastikan GPS aktif dan Anda berada di dalam area lokasi kerja.</p>
    </div>
    <a href="{{ route('absensi.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors self-start">
        <x-icon name="arrow-left" class="w-4 h-4" />
        Kembali
    </a>
</section>

@if (session('success'))
    <x-alert type="success" :message="session('success')" />
@endif
@if (session('error'))
    <x-alert type="error" :message="session('error')" />
@endif
@if ($errors->any())
    <x-alert type="error" message="Ada data yang belum sesuai. Periksa kembali form absensi." />
@endif

<form action="{{ route('absensi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" data-submit-text="Menyimpan absensi...">
    @csrf

    {{-- Basic info --}}
    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Informasi Dasar</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Tanggal & jam terisi otomatis sesuai waktu server.</p>

        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Tanggal</label>
                <input type="text" value="{{ $tanggalHariIni }}" readonly
                       class="mt-1 w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl font-mono-data text-slate-700 dark:text-slate-300">
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Jam</label>
                <input type="text" value="{{ $jamSekarang }}" readonly
                       class="mt-1 w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl font-mono-data text-slate-700 dark:text-slate-300">
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Status</label>
                <select name="status" id="status" required
                        class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                    <option value="standby" {{ old('status') === 'standby' ? 'selected' : '' }}>Standby</option>
                    <option value="progress" {{ old('status') === 'progress' ? 'selected' : '' }}>Progress</option>
                </select>
                @error('status') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </article>

    {{-- Geofencing --}}
    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Validasi Geofencing</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Pilih lokasi resmi, izinkan GPS, lalu pastikan posisi Anda berada di dalam polygon.</p>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-[1fr_1.4fr] gap-5">
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Lokasi Geofence</label>
                    <select name="lokasi_id" id="lokasiSelect" required
                            class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                        <option value="">— Pilih lokasi resmi —</option>
                        @foreach($lokasiList as $lokasi)
                            <option value="{{ $lokasi->id }}"
                                    data-name="{{ $lokasi->nama_lokasi }}"
                                    data-lat="{{ $lokasi->latitude }}"
                                    data-lng="{{ $lokasi->longitude }}"
                                    data-radius="{{ $lokasi->radius }}"
                                    data-polygon="{{ json_encode($lokasi->polygon) }}"
                                    {{ old('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                                {{ $lokasi->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_id') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Cari Lokasi di Maps</label>
                    <div class="mt-1 flex gap-2">
                        <input id="mapSearch" type="text" placeholder="Contoh: Kantor PLN Palembang"
                               class="flex-1 px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                        <button type="button" id="btnSearchMap"
                                class="px-4 py-2.5 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl cursor-pointer focus-ring">
                            Cari
                        </button>
                    </div>
                    <div id="searchResults" class="mt-2 space-y-1"></div>
                </div>

                <div id="statusArea">
                    <x-alert type="warning" message="Menunggu akses GPS. Izinkan lokasi agar tombol absen aktif." />
                </div>

                <button type="button" id="btnRefreshGps"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors">
                    <x-icon name="refresh-cw" class="w-4 h-4" />
                    Ambil Ulang Lokasi GPS
                </button>
            </div>

            <div id="map" class="min-h-[360px] lg:min-h-[420px] rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800" style="isolation:isolate"></div>
        </div>
    </article>

    {{-- Lokasi pekerjaan --}}
    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Lokasi Pekerjaan</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Detail spesifik di mana pekerjaan dilakukan dalam area geofence.</p>

        <input type="text" name="lokasi" id="lokasiKerja" value="{{ old('lokasi') }}"
               placeholder="Contoh: Area panel listrik lantai 2"
               class="mt-4 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
        @error('lokasi') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
    </article>

    {{-- Uraian --}}
    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Uraian Kegiatan</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Tuliskan ringkasan aktivitas atau kondisi standby.</p>

        <textarea name="uraian" rows="4" required placeholder="Tuliskan kegiatan atau kondisi standby..."
                  class="mt-4 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring resize-y">{{ old('uraian') }}</textarea>
        @error('uraian') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
    </article>

    {{-- APD Checklist --}}
    <article id="progressBox" class="surface-card p-5 lg:p-6">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Checklist APD</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Centang APD yang dipakai saat bekerja.</p>
            </div>
            <span class="pill pill-info"><span class="dot"></span>Wajib untuk Progress</span>
        </div>
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            @foreach($apdItems as $item)
                <label class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl hover:border-brand-300 hover:bg-brand-50 dark:hover:bg-brand-900/30 dark:bg-brand-900/20/50 cursor-pointer transition-colors">
                    <input type="checkbox" name="checklist_apd[]" value="{{ $item }}"
                           class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                           {{ in_array($item, old('checklist_apd', []), true) ? 'checked' : '' }}>
                    {{ $item }}
                </label>
            @endforeach
        </div>
        @error('checklist_apd') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-2">{{ $message }}</p> @enderror
    </article>

    {{-- Foto --}}
    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Foto Bukti</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Foto akan diberi cap tanggal, GPS, dan lokasi otomatis.</p>

        {{-- Hidden real file input --}}
        <input type="file" id="fotoRaw" accept="image/*" capture="environment" class="hidden">
        {{-- Hidden input yang dikirim ke server (berisi canvas blob) --}}
        <input type="hidden" name="foto_base64" id="fotoBase64">

        <div class="mt-4 flex flex-col items-center gap-3">
            <button type="button" id="btnAmbilFoto"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Ambil Foto
            </button>

            <canvas id="fotoCanvas" class="hidden w-full rounded-2xl border border-slate-200 dark:border-white/10"></canvas>
            <p id="fotoStatus" class="text-xs text-slate-500 dark:text-slate-400"></p>
        </div>

        @error('foto_base64') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
    </article>

    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

    {{-- Actions --}}
    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
        <a href="{{ route('absensi.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 rounded-full cursor-pointer focus-ring transition-colors">
            Batal
        </a>
        <button id="btnSubmit" type="submit" disabled
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 disabled:bg-slate-300 disabled:cursor-not-allowed rounded-full cursor-pointer focus-ring transition-colors">
            <x-icon name="check-circle" class="w-4 h-4" />
            Absen Sekarang
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const lokasiSelect   = document.getElementById('lokasiSelect');
const statusArea     = document.getElementById('statusArea');
const btnSubmit      = document.getElementById('btnSubmit');
const btnRefreshGps  = document.getElementById('btnRefreshGps');
const statusInput    = document.getElementById('status');
const progressBox    = document.getElementById('progressBox');
const mapSearch      = document.getElementById('mapSearch');
const btnSearchMap   = document.getElementById('btnSearchMap');
const searchResults  = document.getElementById('searchResults');
const lokasiKerja    = document.getElementById('lokasiKerja');
const latitudeInput  = document.getElementById('latitude');
const longitudeInput = document.getElementById('longitude');
const fotoInput      = document.querySelector('input[name="foto"]');
const previewFoto    = document.getElementById('previewFoto');

let map = L.map('map').setView([-2.990934, 104.756554], 13);
let userMarker, searchMarker, polygonLayer;
let userLat = parseFloat(latitudeInput.value) || null;
let userLng = parseFloat(longitudeInput.value) || null;
let userAltitude = null;
let userSpeed = null;
let reverseAddress = null;
let fotoIndexNumber = Math.floor(Math.random() * 9000) + 1000;

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

function setStatus(type, message) {
    const config = {
        success: 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200',
        error:   'text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-500/10 border-red-200',
        warning: 'text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-500/10 border-amber-200',
        info:    'text-sky-700 bg-sky-50 dark:bg-sky-500/10 border-sky-200',
    }[type];
    statusArea.innerHTML = `<div class="inline-flex items-start gap-2 px-4 py-2.5 text-sm font-semibold border rounded-xl w-full ${config}"><span>${message}</span></div>`;
}

function syncProgressBox() {
    progressBox.style.display = statusInput.value === 'progress' ? 'block' : 'none';
}

function selectedLokasi() {
    const opt = lokasiSelect.options[lokasiSelect.selectedIndex];
    if (!opt || !opt.value) return null;
    let polygon = null;
    try { polygon = JSON.parse(opt.dataset.polygon); } catch (e) {}
    return {
        id: opt.value,
        name: opt.dataset.name,
        lat: parseFloat(opt.dataset.lat),
        lng: parseFloat(opt.dataset.lng),
        radius: parseFloat(opt.dataset.radius),
        polygon
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
        polygonLayer = L.polygon(latlngs, { color: '#0284C7', fillColor: '#7DD3FC', fillOpacity: 0.2 }).addTo(map).bindPopup(lokasi.name);
        map.fitBounds(polygonLayer.getBounds());
    } else {
        polygonLayer = L.circle([lokasi.lat, lokasi.lng], { radius: lokasi.radius, color: '#0284C7', fillColor: '#7DD3FC', fillOpacity: 0.2 }).addTo(map);
        map.setView([lokasi.lat, lokasi.lng], 16);
    }
    validateGeofence();
}

function validateGeofence() {
    const lokasi = selectedLokasi();
    btnSubmit.disabled = true;
    if (!lokasi) { setStatus('warning', 'Pilih lokasi geofencing terlebih dahulu.'); return; }
    if (!userLat || !userLng) { setStatus('warning', 'Menunggu GPS. Izinkan akses lokasi dan tekan tombol di atas bila perlu.'); return; }
    let inside = false;
    if (lokasi.polygon && lokasi.polygon.length >= 3) {
        inside = pointInPolygon(userLat, userLng, lokasi.polygon);
    } else {
        inside = map.distance([userLat, userLng], [lokasi.lat, lokasi.lng]) <= lokasi.radius;
    }
    if (inside) {
        setStatus('success', 'Anda berada di dalam area geofencing. Absensi dapat dilakukan.');
        btnSubmit.disabled = false;
    } else {
        setStatus('error', 'Anda berada di luar area geofencing. Pastikan berada di dalam batas wilayah yang ditentukan.');
    }
}

function getGps() {
    setStatus('info', 'Mengambil lokasi GPS...');
    if (!navigator.geolocation) { setStatus('error', 'Browser tidak mendukung geolocation.'); return; }
    navigator.geolocation.getCurrentPosition(
        pos => {
            userLat = pos.coords.latitude;
            userLng = pos.coords.longitude;
            userAltitude = pos.coords.altitude;
            userSpeed = pos.coords.speed;
            latitudeInput.value = userLat;
            longitudeInput.value = userLng;
            if (userMarker) map.removeLayer(userMarker);
            userMarker = L.marker([userLat, userLng]).addTo(map).bindPopup('Lokasi Anda');
            map.setView([userLat, userLng], 16);
            validateGeofence();
            // Reverse geocode untuk caption foto
            fetchReverseGeocode(userLat, userLng);
        },
        () => setStatus('error', 'GPS tidak diizinkan atau gagal didapatkan. Aktifkan GPS lalu coba lagi.'),
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );
}

async function fetchReverseGeocode(lat, lng) {
    try {
        const r = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`, {
            headers: { 'Accept-Language': 'id' }
        });
        const data = await r.json();
        reverseAddress = data.address || null;
    } catch (e) { reverseAddress = null; }
}

async function searchMap() {
    const q = mapSearch.value.trim();
    if (!q) return;
    searchResults.innerHTML = '<div class="text-xs font-semibold text-slate-500 dark:text-slate-400 dark:text-slate-500">Mencari lokasi...</div>';
    try {
        const r = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=5&q=${encodeURIComponent(q)}`);
        const data = await r.json();
        if (!data.length) { searchResults.innerHTML = '<div class="text-xs font-semibold text-red-600 dark:text-red-400">Lokasi tidak ditemukan.</div>'; return; }
        searchResults.innerHTML = data.map((item, i) =>
            `<button type="button" data-idx="${i}" class="map-result block w-full text-left text-[11px] font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60 px-3 py-2 rounded-lg cursor-pointer focus-ring">${item.display_name}</button>`
        ).join('');
        document.querySelectorAll('.map-result').forEach(btn => {
            btn.addEventListener('click', function () {
                const item = data[this.dataset.idx];
                const lat = parseFloat(item.lat), lng = parseFloat(item.lon);
                if (searchMarker) map.removeLayer(searchMarker);
                searchMarker = L.marker([lat, lng]).addTo(map).bindPopup(item.display_name).openPopup();
                map.setView([lat, lng], 16);
                lokasiKerja.value = item.display_name;
            });
        });
    } catch (e) { searchResults.innerHTML = '<div class="text-xs font-semibold text-red-600 dark:text-red-400">Pencarian gagal. Periksa koneksi.</div>'; }
}

statusInput.addEventListener('change', syncProgressBox);
lokasiSelect.addEventListener('change', drawSelectedLokasi);
btnRefreshGps.addEventListener('click', getGps);
btnSearchMap.addEventListener('click', searchMap);
mapSearch.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); searchMap(); } });

syncProgressBox();
drawSelectedLokasi();
getGps();

// ─── Foto dengan caption GPS ─────────────────────────────────────────────────
const btnAmbilFoto = document.getElementById('btnAmbilFoto');
const fotoRaw      = document.getElementById('fotoRaw');
const fotoCanvas   = document.getElementById('fotoCanvas');
const fotoBase64   = document.getElementById('fotoBase64');
const fotoStatus   = document.getElementById('fotoStatus');

btnAmbilFoto.addEventListener('click', () => fotoRaw.click());

fotoRaw.addEventListener('change', async e => {
    const file = e.target.files[0];
    if (!file) return;

    const img = new Image();
    img.onload = async () => {
        const W = img.naturalWidth;
        const H = img.naturalHeight;

        fotoCanvas.width  = W;
        fotoCanvas.height = H;
        const ctx = fotoCanvas.getContext('2d');

        // Gambar foto asli
        ctx.drawImage(img, 0, 0, W, H);

        // ── Bangun teks caption ───────────────────────────────────────────
        const now = new Date();
        const wibOptions = { timeZone: 'Asia/Jakarta', weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' };
        const wibTimeOptions = { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        const dateStr = new Intl.DateTimeFormat('id-ID', wibOptions).format(now);
        const timeStr = new Intl.DateTimeFormat('id-ID', wibTimeOptions).format(now).replace(/:/g, '.') + ' WIB';

        const latVal  = userLat  !== null ? Math.abs(userLat).toFixed(5) + (userLat  >= 0 ? 'N' : 'S') : '—';
        const lngVal  = userLng  !== null ? Math.abs(userLng).toFixed(5) + (userLng >= 0 ? 'E' : 'W') : '—';
        const coordStr = `${latVal} ${lngVal}`;

        // Susun alamat menjadi 2 baris dari reverse geocode
        let addrLine1 = '', addrLine2 = '';
        if (reverseAddress) {
            const a = reverseAddress;
            const kel  = a.suburb || a.village || a.hamlet || a.neighbourhood || '';
            const kec  = a.city_district || a.district || a.municipality || ''; // kecamatan (urban/rural)
            const kab  = a.county || a.city || a.town || '';                    // kabupaten/kota (bukan municipality)
            const prov = a.state || '';
            addrLine1 = [kel, kec].filter(Boolean).join(', ');
            addrLine2 = [kab, prov].filter(Boolean).join(', ');
        }

        const lines = [
            `${dateStr} ${timeStr}`,
            coordStr,
            ...(addrLine1 ? [addrLine1] : []),
            ...(addrLine2 ? [addrLine2] : ['Lokasi tidak diketahui']),
        ];

        // ── Hitung ukuran & posisi teks (pojok kanan bawah) ──────────────
        const scale    = W / 1080;
        const fontSize = Math.round(32 * scale);
        const lineH    = Math.round(fontSize * 1.5);
        const padX     = Math.round(28 * scale);
        const padY     = Math.round(22 * scale);

        // ── Teks kuning tanpa background, pojok kanan bawah ──────────────
        ctx.font        = `bold ${fontSize}px Arial, sans-serif`;
        ctx.textAlign   = 'right';
        ctx.textBaseline = 'bottom';

        // Shadow tebal agar teks terbaca di atas foto terang/gelap
        ctx.shadowColor   = 'rgba(0,0,0,0.85)';
        ctx.shadowBlur    = Math.round(8 * scale);
        ctx.shadowOffsetX = Math.round(2 * scale);
        ctx.shadowOffsetY = Math.round(2 * scale);
        ctx.fillStyle   = '#FFE033';

        const x = W - padX;
        lines.slice().reverse().forEach((line, i) => {
            const y = H - padY - i * lineH;
            ctx.fillText(line, x, y);
        });

        // ── Tampilkan preview & simpan base64 ─────────────────────────────
        fotoCanvas.classList.remove('hidden');
        const dataUrl = fotoCanvas.toDataURL('image/jpeg', 0.88);
        fotoBase64.value = dataUrl;
        btnAmbilFoto.textContent = 'Ganti Foto';

        URL.revokeObjectURL(img.src);
    };
    img.src = URL.createObjectURL(file);
});

// Validasi: foto wajib sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    if (!fotoBase64.value) {
        e.preventDefault();
        fotoStatus.textContent = '⚠ Foto belum diambil. Klik "Ambil Foto" terlebih dahulu.';
        fotoStatus.classList.add('text-red-600', 'dark:text-red-400');
        btnAmbilFoto.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endpush
