<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;

Route::get('/export/laporan-keuangan', [ExportController::class, 'laporanKeuangan']);
Route::post('/laporan/import', [ImportController::class, 'import'])
    ->name('laporan.import');
