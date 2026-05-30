<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Lokasi;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
            'lokasi_id'    => ['required', 'exists:lokasis,id'],
            'status'       => ['required', Rule::in(['standby', 'progress'])],
            'lokasi'       => ['required_if:status,progress', 'nullable', 'string', 'max:255'],
            'uraian'       => ['required', 'string'],
            'checklist_apd'   => ['required_if:status,progress', 'nullable', 'array'],
            'checklist_apd.*' => ['string', Rule::in(self::APD_ITEMS)],
            'latitude'     => ['required', 'numeric', 'between:-90,90'],
            'longitude'    => ['required', 'numeric', 'between:-180,180'],
            'foto_base64'  => ['required', 'string'],
        ]);

        $lokasi = Lokasi::findOrFail($validated['lokasi_id']);

        if (!$lokasi->containsPoint((float) $validated['latitude'], (float) $validated['longitude'])) {
            return back()
                ->withInput()
                ->with('error', 'Anda berada di luar area geofencing. Pastikan Anda berada di dalam batas wilayah lokasi yang ditentukan.');
        }

        $now = now(config('app.timezone'));

        // Decode base64 foto dan simpan sebagai JPEG
        $dataUrl  = $validated['foto_base64'];
        $base64   = preg_replace('/^data:image\/\w+;base64,/', '', $dataUrl);
        $imgData  = base64_decode($base64);
        $fileName = 'absensi/' . uniqid('foto_', true) . '.jpg';
        Storage::disk('public')->put($fileName, $imgData);

        $absensi = Absensi::create([
            'user_id'       => Auth::id(),
            'lokasi_id'     => $lokasi->id,
            'tanggal'       => $now->toDateString(),
            'jam'           => $now->format('H:i:s'),
            'status'        => $validated['status'],
            'lokasi'        => $validated['lokasi'] ?? $lokasi->nama_lokasi,
            'uraian'        => $validated['uraian'],
            'checklist_apd' => $validated['checklist_apd'] ?? [],
            'latitude'      => $validated['latitude'],
            'longitude'     => $validated['longitude'],
            'foto'          => $fileName,
        ]);

        // Notify all supervisors about the new absensi
        $petugasName = Auth::user()->name;
        $statusLabel = ucfirst($validated['status']);
        $lokasiName  = $validated['lokasi'] ?? $lokasi->nama_lokasi;

        $supervisors = User::where('role', 'supervisor')->pluck('id');
        $notifData = $supervisors->map(fn ($id) => [
            'user_id'    => $id,
            'absensi_id' => $absensi->id,
            'judul'      => 'Absensi Baru',
            'pesan'      => "{$petugasName} menambahkan absensi {$statusLabel} di {$lokasiName}",
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        Notifikasi::insert($notifData);

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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Absensi');

        // Column letters mapping
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        // ── Title rows ──
        $sheet->setCellValue('A1', 'Laporan Absensi K3L');
        $sheet->setCellValue('A2', 'Periode: ' . $range['tanggal_dari'] . ' sampai ' . $range['tanggal_sampai']);
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2')->getFont()->setSize(11)->setItalic(true);

        // ── Header row ──
        $headers = ['No', 'Nama Petugas', 'Email', 'Tanggal', 'Jam Absen', 'Status', 'Lokasi Geofencing', 'Lokasi Pekerjaan', 'Checklist APD', 'Uraian', 'Foto'];
        $headerRow = 4;

        foreach ($headers as $col => $heading) {
            $sheet->setCellValue($columns[$col] . $headerRow, $heading);
        }

        $headerStyle = $sheet->getStyle('A' . $headerRow . ':K' . $headerRow);
        $headerStyle->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF0F172A');
        $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        // ── Column widths ──
        $widths = [6, 22, 28, 14, 12, 12, 22, 22, 30, 35, 20];
        foreach ($widths as $i => $w) {
            $sheet->getColumnDimension($columns[$i])->setWidth($w);
        }

        // ── Data rows ──
        $imageHeight = 120; // px for each photo
        $currentRow = $headerRow + 1;

        foreach ($items as $index => $item) {
            $rowData = [
                $index + 1,
                $item->user->name ?? 'User tidak ditemukan',
                $item->user->email ?? '-',
                optional($item->tanggal)->format('d M Y') ?? $item->tanggal,
                $this->formatJam($item->jam),
                ucfirst($item->status),
                $item->lokasiData->nama_lokasi ?? '-',
                $item->lokasi ?? '-',
                $item->checklist_apd ? implode(', ', $item->checklist_apd) : '-',
                $item->uraian ?? '-',
            ];

            foreach ($rowData as $col => $value) {
                $sheet->setCellValue($columns[$col] . $currentRow, $value);
            }

            // ── Embed photo ──
            if ($item->foto && Storage::disk('public')->exists($item->foto)) {
                $photoPath = Storage::disk('public')->path($item->foto);

                $drawing = new Drawing();
                $drawing->setName('Foto Absensi');
                $drawing->setDescription('Foto absensi ' . ($item->user->name ?? ''));
                $drawing->setPath($photoPath);
                $drawing->setHeight($imageHeight);
                $drawing->setCoordinates('K' . $currentRow);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawing->setWorksheet($sheet);

                // Adjust row height to accommodate the image + padding
                $sheet->getRowDimension($currentRow)->setRowHeight($imageHeight * 0.75 + 10);
            } else {
                $sheet->setCellValue('K' . $currentRow, '-');
            }

            // Vertical alignment for all cells in this row
            $sheet->getStyle('A' . $currentRow . ':K' . $currentRow)
                ->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setWrapText(true);

            $currentRow++;
        }

        if ($items->isEmpty()) {
            $sheet->setCellValue('A' . $currentRow, 'Tidak ada data absensi pada periode ini.');
            $sheet->mergeCells('A' . $currentRow . ':K' . $currentRow);
            $currentRow++;
        }

        // ── Borders for entire data range ──
        $lastDataRow = $currentRow - 1;
        $sheet->getStyle('A' . $headerRow . ':K' . $lastDataRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setARGB('FFCBD5E1');

        // ── Generate and download ──
        $fileName = 'laporan-absensi-' . $range['tanggal_dari'] . '-sampai-' . $range['tanggal_sampai'] . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
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
