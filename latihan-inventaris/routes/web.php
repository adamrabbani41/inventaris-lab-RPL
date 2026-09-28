<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;

// Redirect halaman utama langsung ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route Dashboard
Route::get('/dashboard', [BarangController::class, 'dashboard'])->name('dashboard');

// Resource Route untuk CRUD Barang
Route::resource('barang', BarangController::class);