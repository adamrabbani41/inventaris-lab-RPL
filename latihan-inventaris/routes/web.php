<?php

use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaPeminjamanController;
use Illuminate\Support\Facades\Route;

// Halaman Awal diarahkan langsung ke halaman Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute untuk semua pengguna yang sudah login (Laboran & Siswa)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================================================================
// GRUP RUTE KHUSUS LABORAN (Middleware: auth + role:laboran)
// =========================================================================
Route::middleware(['auth', 'role:laboran'])->group(function () {
    // Master Data dari v1
    Route::resource('kategori', KategoriController::class);
    Route::resource('barang', BarangController::class);

    // Manajemen Peminjaman & Persetujuan v2
    Route::get('/admin/peminjaman', [AdminPeminjamanController::class, 'index'])->name('admin.peminjaman.index');
    Route::patch('/admin/peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('admin.peminjaman.approve');
    Route::patch('/admin/peminjaman/{id}/reject', [AdminPeminjamanController::class, 'reject'])->name('admin.peminjaman.reject');
    Route::patch('/admin/peminjaman/{id}/return', [AdminPeminjamanController::class, 'returnItem'])->name('admin.peminjaman.return');

    // Ekspor Laporan PDF v2
    Route::get('/admin/laporan/pdf', [LaporanController::class, 'cetakPdf'])->name('admin.laporan.pdf');
});

// =========================================================================
// GRUP RUTE KHUSUS SISWA (Middleware: auth + role:siswa)
// =========================================================================
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
    Route::post('/peminjaman', [SiswaPeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/riwayat-pinjam', [SiswaPeminjamanController::class, 'index'])->name('peminjaman.riwayat');
});

require __DIR__.'/auth.php';
