<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AbsensiController extends Controller
{
    private const APD_ITEMS = [
        'Helm Safety',
        'Baju Pelindung',
        'Tali Pengaman',
        'Sarung Tangan Isolasi',
        'Sepatu Boots Isolasi',
        'Pelindung Wajah',
        'Kacamata Pelindung',
        'Kelengkapan Peralatan Uji',
    ];

    public function index(Request $request)
    {
        $range = $this->dateRange($request);

        $query = Absensi::with(['user', 'lokasiData'])
            ->whereBetween('tanggal', [$range['tanggal_dari'], $range['tanggal_sampai']])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam');

        if (Auth::user()->role === 'petugas') {
            $query->where('user_id', Auth::id());
        }

        return view('absensi.index', [
            'absensi' => $query->get(),
            'tanggalDari' => $range['tanggal_dari'],
            'tanggalSampai' => $range['tanggal_sampai'],
        ]);
    }

    public function create()
    {
        abort_unless(Auth::user()->role === 'petugas', 403);

        $now = now(config('app.timezone'));

        return view('absensi.create', [
            'apdItems' => self::APD_ITEMS,
            'tanggalHariIni' => $now->format('d M Y'),
            'jamSekarang' => $now->format('H:i'),
            'lokasiList' => Lokasi::orderBy('nama_lokasi')->get(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'petugas', 403);

        $validated = $request->validate([
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'status' => ['required', Rule::in(['standby', 'progress'])],
            'lokasi' => ['required_if:status,progress', 'nullable', 'string', 'max:255'],
            'uraian' => ['required', 'string'],
            'checklist_apd' => ['required_if:status,progress', 'nullable', 'array'],
            'checklist_apd.*' => ['string', Rule::in(self::APD_ITEMS)],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'foto' => ['required', 'image', 'max:10240'],
        ]);

        $lokasi = Lokasi::findOrFail($validated['lokasi_id']);

        if (!$lokasi->containsPoint((float) $validated['latitude'], (float) $validated['longitude'])) {
            return back()
                ->withInput()
                ->with('error', 'Anda berada di luar area geofencing. Pastikan Anda berada di dalam batas wilayah lokasi yang ditentukan.');
        }

        $now = now(config('app.timezone'));

        Absensi::create([
            'user_id' => Auth::id(),
            'lokasi_id' => $lokasi->id,
            'tanggal' => $now->toDateString(),
            'jam' => $now->format('H:i:s'),
            'status' => $validated['status'],
            'lokasi' => $validated['lokasi'] ?? $lokasi->nama_lokasi,
            'uraian' => $validated['uraian'],
            'checklist_apd' => $validated['checklist_apd'] ?? [],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'foto' => $request->file('foto')->store('absensi', 'public'),
        ]);

        return redirect()
            ->route('absensi.index', [
                'tanggal_dari' => $now->toDateString(),
                'tanggal_sampai' => $now->toDateString(),
            ])
            ->with('success', 'Absensi berhasil disimpan. Lokasi Anda sudah tervalidasi dalam area geofencing.');
    }

    public function show(Absensi $absensi)
    {
        if (Auth::user()->role === 'petugas' && $absensi->user_id !== Auth::id()) {
            abort(403);
        }

        $absensi->load(['user', 'lokasiData']);

        return view('absensi.show', compact('absensi'));
    }

    public function edit(Absensi $absensi)
    {
        abort_unless(Auth::user()->role === 'supervisor', 403);

        return view('absensi.edit', [
            'absensi' => $absensi->load(['user', 'lokasiData']),
            'apdItems' => self::APD_ITEMS,
        ]);
    }

    public function update(Request $request, Absensi $absensi)
    {
        abort_unless(Auth::user()->role === 'supervisor', 403);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'status' => ['required', Rule::in(['standby', 'progress'])],
            'lokasi' => ['required_if:status,progress', 'nullable', 'string', 'max:255'],
            'uraian' => ['required', 'string'],
            'checklist_apd' => ['nullable', 'array'],
            'checklist_apd.*' => ['string', Rule::in(self::APD_ITEMS)],
            'foto' => ['nullable', 'image', 'max:10240'],
        ]);

        if ($request->hasFile('foto')) {
            if ($absensi->foto) {
                Storage::disk('public')->delete($absensi->foto);
            }

            $validated['foto'] = $request->file('foto')->store('absensi', 'public');
        }

        $absensi->update([
            'tanggal' => $validated['tanggal'],
            'jam' => $validated['jam'] . ':00',
            'status' => $validated['status'],
            'lokasi' => $validated['lokasi'] ?? null,
            'uraian' => $validated['uraian'],
            'checklist_apd' => $validated['checklist_apd'] ?? [],
            'foto' => $validated['foto'] ?? $absensi->foto,
        ]);

        return redirect()
            ->route('absensi.index', [
                'tanggal_dari' => $validated['tanggal'],
                'tanggal_sampai' => $validated['tanggal'],
            ])
            ->with('success', 'Absensi berhasil diupdate.');
    }

    public function destroy(Absensi $absensi)
    {
        abort_unless(Auth::user()->role === 'supervisor', 403);

        if ($absensi->foto) {
            Storage::disk('public')->delete($absensi->foto);
        }

        $tanggal = optional($absensi->tanggal)->toDateString() ?? today(config('app.timezone'))->toDateString();

        $absensi->delete();

        return redirect()
            ->route('absensi.index', [
                'tanggal_dari' => $tanggal,
                'tanggal_sampai' => $tanggal,
            ])
            ->with('success', 'Absensi berhasil dihapus.');
    }

    public function download(Request $request)
    {
        abort_unless(Auth::user()->role === 'supervisor', 403);

        $range = $this->dateRange($request);

        $items = Absensi::with(['user', 'lokasiData'])
            ->whereBetween('tanggal', [$range['tanggal_dari'], $range['tanggal_sampai']])
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get();

        $fileName = 'laporan-absensi-' . $range['tanggal_dari'] . '-sampai-' . $range['tanggal_sampai'] . '.xls';

        return response()->streamDownload(function () use ($items, $range) {
            echo '<html><head><meta charset="UTF-8">';
            echo '<style>
                table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }
                th { background: #0f172a; color: #ffffff; font-weight: bold; }
                th, td { border: 1px solid #cbd5e1; padding: 8px; vertical-align: top; }
                .title { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
                .range { margin-bottom: 16px; color: #475569; }
            </style>';
            echo '</head><body>';
            echo '<div class="title">Laporan Absensi K3L</div>';
            echo '<div class="range">Periode: ' . e($range['tanggal_dari']) . ' sampai ' . e($range['tanggal_sampai']) . '</div>';
            echo '<table><thead><tr>';

            foreach (['No', 'Nama Petugas', 'Email', 'Tanggal', 'Jam Absen', 'Status', 'Lokasi Geofence', 'Lokasi Pekerjaan', 'Checklist APD', 'Uraian'] as $heading) {
                echo '<th>' . e($heading) . '</th>';
            }

            echo '</tr></thead><tbody>';

            foreach ($items as $index => $item) {
                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td>' . e($item->user->name ?? 'User tidak ditemukan') . '</td>';
                echo '<td>' . e($item->user->email ?? '-') . '</td>';
                echo '<td>' . e(optional($item->tanggal)->format('d M Y') ?? $item->tanggal) . '</td>';
                echo '<td>' . e($this->formatJam($item->jam)) . '</td>';
                echo '<td>' . e(ucfirst($item->status)) . '</td>';
                echo '<td>' . e($item->lokasiData->nama_lokasi ?? '-') . '</td>';
                echo '<td>' . e($item->lokasi ?? '-') . '</td>';
                echo '<td>' . e($item->checklist_apd ? implode(', ', $item->checklist_apd) : '-') . '</td>';
                echo '<td>' . e($item->uraian ?? '-') . '</td>';
                echo '</tr>';
            }

            if ($items->isEmpty()) {
                echo '<tr><td colspan="10">Tidak ada data absensi pada periode ini.</td></tr>';
            }

            echo '</tbody></table></body></html>';
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    private function dateRange(Request $request): array
    {
        $validated = $request->validate([
            'tanggal_dari' => ['nullable', 'date'],
            'tanggal_sampai' => ['nullable', 'date', 'after_or_equal:tanggal_dari'],
        ]);

        $today = today(config('app.timezone'))->toDateString();
        $tanggalDari = $validated['tanggal_dari'] ?? $today;
        $tanggalSampai = $validated['tanggal_sampai'] ?? $tanggalDari;

        return [
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
        ];
    }

    private function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    private function formatJam(?string $jam): string
    {
        return $jam ? substr($jam, 0, 5) : '-';
    }
}
