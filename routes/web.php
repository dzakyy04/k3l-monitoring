<?php

use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LokasiController;
use App\Models\Absensi;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return redirect()->route('login');

});


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {

        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR
        |--------------------------------------------------------------------------
        */

        if (auth()->user()->role == 'supervisor') {

            $today = today();

            return view('supervisor.dashboard', [
                'totalPetugas'    => User::where('role', 'petugas')->count(),
                'totalSupervisor' => User::where('role', 'supervisor')->count(),
                'totalLokasi'     => Lokasi::count(),
                'absensiHariIni'  => Absensi::whereDate('tanggal', $today)->count(),
                'progressHariIni' => Absensi::whereDate('tanggal', $today)->where('status', 'progress')->count(),
                'standbyHariIni'  => Absensi::whereDate('tanggal', $today)->where('status', 'standby')->count(),
                'absensiTerbaru'  => Absensi::with(['user', 'lokasiData'])
                    ->whereDate('tanggal', $today)
                    ->orderByDesc('jam')
                    ->take(6)
                    ->get(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PETUGAS
        |--------------------------------------------------------------------------
        */

        return view('petugas.dashboard', [

            'absensiHariIni' => Absensi::with('lokasiData')
                ->where('user_id', auth()->id())
                ->whereDate('tanggal', today())
                ->latest()
                ->first(),

            'totalAbsensi' => Absensi::where(
                'user_id',
                auth()->id()
            )->count(),

            'progressCount' => Absensi::where(
                'user_id',
                auth()->id()
            )
            ->where('status', 'progress')
            ->count(),

            'absensiBulanIni' => Absensi::where('user_id', auth()->id())
                ->whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->count(),

            'riwayatTerbaru' => Absensi::with('lokasiData')
                ->where('user_id', auth()->id())
                ->orderByDesc('tanggal')
                ->orderByDesc('jam')
                ->take(5)
                ->get(),

        ]);

    })->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| SUPERVISOR ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:supervisor'])->group(function () {

    Route::resource('petugas', PetugasController::class)
        ->except(['show', 'create', 'edit']);

    Route::resource('lokasi', LokasiController::class);

});

/*
|--------------------------------------------------------------------------
| ABSENSI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get(
        'absensi/download',
        [AbsensiController::class, 'download']
    )->name('absensi.download');

    Route::resource(
        'absensi',
        AbsensiController::class
    );

});

require __DIR__.'/auth.php';
