<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

// Export and Import routes for Laporan Keuangan
Route::get('/export/laporan', [LaporanController::class, 'export']);
Route::post('/laporan/import', [ImportController::class, 'import'])
    ->name('laporan.import');

