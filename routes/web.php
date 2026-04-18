<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LaporanKeuanganPdfController;

Route::get('/', fn () => redirect('/admin'));
Route::get('/export/laporan-keuangan', [ExportController::class, 'laporanKeuangan']);
Route::get('/laporan-keuangan/pdf/{tahun}', [LaporanKeuanganPdfController::class, 'generate'])
    ->name('laporan-keuangan.pdf');
Route::post('/laporan/import', [ImportController::class, 'import'])
    ->name('laporan.import');
