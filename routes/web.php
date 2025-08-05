<?php

use App\Http\Controllers\AddController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KompetisiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LombaController;
use App\Models\Kompetisi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KlubController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\RandomizerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TimerController;
use App\Models\Lomba;
use App\Http\Controllers\ExportPdfController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\ExportController;

// Halaman Beranda
Route::get('/', [KompetisiController::class, 'index'])->name('index');

// Halaman Kompetisi
Route::get('/kompetisi', function () {
    return view('kompetisi');
})->name('kompetisi');

// Halaman Jadwal
Route::get('/hasil', function () {
    return view('hasil');
})->name('hasil');

// Halaman Atlet
Route::get('/atlet', function () {
    return view('atlet');
})->name('atlet');

// Halaman More
Route::get('/more', function () {
    return view('more');
})->name('more');

Route::get('/lihat_kompetisi/{id}', [KompetisiController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('lihat_kompetisi');
Route::get('/user_kompetisi/{id}', [KompetisiController::class, 'lihat'])->name('user_kompetisi');
Route::get('/lihat_hasil/{id}', [HasilController::class, 'lihat'])->name('lihat_hasil');
Route::get('/hasil', [HasilController::class, 'tampil'])->name('hasil');
Route::get('/kompetisi/{id}/bagi-peserta', [KompetisiController::class, 'bagiPeserta'])->name('bagi_peserta');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/add', [AddController::class, 'showAddView'])
    ->middleware(['auth', 'verified'])
    ->name('add');
Route::get('/list', [AddController::class, 'list'])
    ->middleware(['auth', 'verified'])
    ->name('list');
Route::get('/list_kompetisi', [KompetisiController::class, 'showListView'])
    ->middleware(['auth', 'verified'])
    ->name('list_kompetisi');
Route::get('/kompetisi', [KompetisiController::class, 'kompetisi'])
    ->name('kompetisi');
Route::get('/add_kompetisi', [KompetisiController::class, 'showAddView'])
    ->middleware(['auth', 'verified'])
    ->name('add_kompetisi');
Route::get('/atlet', [AddController::class, 'atlet'])
    ->name('atlet');
Route::get('/list_kompetisi', [KompetisiController::class, 'list_kompetisi'])
    ->middleware(['auth', 'verified'])
    ->name('list_kompetisi');
Route::delete('/kompetisi/{id}', [KompetisiController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('hapus_kompetisi');

// Klub
Route::get('/edit_klub/{id}', [App\Http\Controllers\KlubController::class, 'edit'])->name('edit_klub');
Route::put('/klub/{id}', [App\Http\Controllers\KlubController::class, 'update'])->name('update_klub');
Route::delete('/klub/{id}', [App\Http\Controllers\KlubController::class, 'destroy'])->name('hapus_klub');


Route::get('/kompetisi/{id}/tambah_lomba', [LombaController::class, 'create'])->name('tambah_lomba');
Route::get('/klub', [KlubController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('klub');

Route::post('/start-series', [TimerController::class, 'startSeries'])->name('timer.seri.start');
Route::post('/stop-series', [TimerController::class, 'stopSeries']);
Route::post('/stop-series', [LombaController::class, 'stopSeries']);
Route::post('/update-hasil/{id}', [KompetisiController::class, 'updateHasil'])->name('update_hasil');

Route::post('/get-times', [TimerController::class, 'getTimes'])->name('timer.get.times');


Route::put('/kompetisi/{id}/update', [KompetisiController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('update_kompetisi');

Route::get('/admin_hasil', [HasilController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin_hasil');
Route::get('/hasil_juara/{id}', [HasilController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('hasil_juara');

Route::post('/hasil/simpan', [HasilController::class, 'simpan'])
    ->middleware(['auth', 'verified'])
    ->name('simpan.hasil');

// Route to store data
Route::post('/store-data', [AddController::class, 'storeData']);
Route::post('/store_kompetisi', [KompetisiController::class, 'storeData'])->name('store_kompetisi');
Route::post('/store_kompetisi', [KompetisiController::class, 'storeData'])->name('store_kompetisi');
Route::post('/kompetisi/{id}/tambah_lomba', [LombaController::class, 'store'])->name('simpan_lomba');

// Routes for editing and deleting participants
Route::get('/peserta/{id}/edit', [AddController::class, 'edit'])->name('peserta.edit');
Route::put('/peserta/{id}', [AddController::class, 'update'])->name('peserta.update');
Route::delete('/peserta/{id}', [AddController::class, 'destroy'])->name('peserta.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk klub
Route::middleware(['auth', 'role:klub'])->group(function () {
    // Input peserta
    Route::get('/add', [AddController::class, 'showAddView'])->name('add');
    Route::get('/add', [AddController::class, 'create'])->name('add');
    Route::post('/store-data', [AddController::class, 'storeData']);
    Route::get('/info-klub', [KlubController::class, 'info'])->name('info.klub');
    Route::get('/kompetisi/klub', [KompetisiController::class, 'klub'])->name('kompetisi.klub');
    Route::get('/kompetisi/klub/{id}/peserta', [KompetisiController::class, 'klubPesertaKompetisi'])->name('kompetisi.klub.peserta');
});

Route::middleware('api')->group(function () {
    Route::get('/lomba/{kompetisi}', function ($kompetisi) {
        return Lomba::where('kompetisi_id', $kompetisi)->get();
    });
});

// Rute untuk menampilkan daftar lomba
Route::get('/lomba/{kompetisi}', function ($kompetisi) {
    return Lomba::where('kompetisi_id', $kompetisi)->get();
});


Route::put('/lomba/{id}', [LombaController::class, 'update'])->name('update_lomba');
Route::get('/edit_lomba/{id}', [LombaController::class, 'edit'])->name('edit_lomba');
Route::delete('/lomba/{id}', [LombaController::class, 'destroy'])->name('hapus_lomba');

// Rute untuk menampilkan form registrasi klub
Route::get('/register-klub', [KlubController::class, 'showRegisterForm'])->name('register_klub_form');

// Rute untuk memproses registrasi klub
Route::post('/register-klub', [KlubController::class, 'registerKlub'])->name('register_klub');

Route::middleware(['role:klub'])->get('/test-role', function () {
    return 'Middleware Role Berfungsi!';
});

Route::get('/export/hasil/{id}', [ExportPdfController::class, 'hasilKompetisi'])->name('export.hasil_pdf');
Route::get('/export/hasil-kompetisi/{id}', [ExportPdfController::class, 'acara'])->name('export.buku_acara_pdf');

Route::get('/hasil/export/excel/{id}', [ExportController::class, 'exportExcel'])->name('hasil.export.excel');

Route::post('/kompetisi/{id}/randomize-all', [KompetisiController::class, 'randomizeAllPeserta'])->name('randomize_all_peserta');
Route::post('/kompetisi/{id}/sort-all-peserta', [KompetisiController::class, 'sortAllPeserta'])
    ->name('sort_all_peserta');
Route::post('/lomba/{lomba_id}/seri/{seri}/center-max-limit', [KompetisiController::class, 'centerMaxLimitPeserta'])
    ->name('center_max_limit_peserta');

require __DIR__ . '/auth.php';
