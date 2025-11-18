<?php

use Illuminate\Support\Facades\Route;

// Halaman depan / landing page
Route::get('/', function () {
    return view('welcome');
});
