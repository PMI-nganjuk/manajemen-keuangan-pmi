<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengeluaranKasController;
use App\Http\Controllers\PenerimaanKasController;



Route::get('/', function () {
    return view('welcome');
});

// Contoh route untuk ADMIN
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Admin Dashboard';
    });
});

// Contoh route untuk STAF_KEUANGAN
Route::middleware(['role:staf_keuangan'])->group(function () {
    Route::get('/keuangan/pengeluaran', function () {
        return 'Halaman Pengeluaran';
    });
    Route::get('/keuangan/pemasukan', function () {
        return 'Halaman Pemasukan';
    });
});

// Contoh route untuk MANAJER_KEUANGAN
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

// Contoh route untuk PEGAWAI
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

