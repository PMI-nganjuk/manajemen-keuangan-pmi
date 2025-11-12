<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengeluaranKasController;
use App\Http\Controllers\PenerimaanKasController;
use App\Http\Controllers\KategoriSatuController;
use App\Http\Controllers\KategoriDuaController;
use App\Http\Controllers\ProgramKerjaController;



Route::get('/', function () {
    return view('welcome');
});

// route untuk admin
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Admin Dashboard';
    });
});

// route untuk staf_keuangan
Route::middleware(['role:staf_keuangan'])->group(function () {
    Route::get('/keuangan/pengeluaran', function () {
        return 'Halaman Pengeluaran';
    });
    Route::get('/keuangan/pemasukan', function () {
        return 'Halaman Pemasukan';
    });
});

// route untuk manager_keuangan
Route::middleware(['role:manager_keuangan'])->group(function () {
    Route::get('/keuangan/coa', function () {
        return 'Halaman Chart of Account';
    });
    Route::get('/keuangan/program-kerja', function () {
        return 'Halaman Program Kerja';
    });
    Route::get('/keuangan/kategori', function () {
        return 'Halaman Kategori';
    });
});

//route untuk pegawai biasa
Route::middleware(['role:pegawai'])->group(function () {
    Route::get('/pegawai/view-data', function () {
        return 'Halaman View Data';
    });
});

// Route untuk Pengeluaran dan Penerimaan Kas
Route::middleware(['role:admin,staf_keuangan,manajer_keuangan,pegawai'])->group(function () {
    Route::get('/pengeluaran', [PengeluaranKasController::class, 'index']);
    Route::get('/pengeluaran/{id}', [PengeluaranKasController::class, 'show']);
    Route::post('/pengeluaran', [PengeluaranKasController::class, 'store']);
    Route::put('/pengeluaran/{id}', [PengeluaranKasController::class, 'update']);
    Route::delete('/pengeluaran/{id}', [PengeluaranKasController::class, 'destroy']);

    Route::get('/penerimaan', [PenerimaanKasController::class, 'index']);
    Route::get('/penerimaan/{id}', [PenerimaanKasController::class, 'show']);
    Route::post('/penerimaan', [PenerimaanKasController::class, 'store']);
    Route::put('/penerimaan/{id}', [PenerimaanKasController::class, 'update']);
    Route::delete('/penerimaan/{id}', [PenerimaanKasController::class, 'destroy']);
});

// Kategori Satu
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/kategori-satu', [KategoriSatuController::class, 'index']);
    Route::get('/kategori-satu/{id}', [KategoriSatuController::class, 'show']);
    Route::post('/kategori-satu', [KategoriSatuController::class, 'store']);
    Route::put('/kategori-satu/{id}', [KategoriSatuController::class, 'update']);
    Route::delete('/kategori-satu/{id}', [KategoriSatuController::class, 'destroy']);

    // Kategori Dua
    Route::get('/kategori-dua', [KategoriDuaController::class, 'index']);
    Route::get('/kategori-dua/{id}', [KategoriDuaController::class, 'show']);
    Route::post('/kategori-dua', [KategoriDuaController::class, 'store']);
    Route::put('/kategori-dua/{id}', [KategoriDuaController::class, 'update']);
    Route::delete('/kategori-dua/{id}', [KategoriDuaController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/program-kerja', [ProgramKerjaController::class, 'index']);
    Route::get('/program-kerja/{id}', [ProgramKerjaController::class, 'show']);
    Route::post('/program-kerja', [ProgramKerjaController::class, 'store']);
    Route::put('/program-kerja/{id}', [ProgramKerjaController::class, 'update']);
    Route::delete('/program-kerja/{id}', [ProgramKerjaController::class, 'destroy']);
});
