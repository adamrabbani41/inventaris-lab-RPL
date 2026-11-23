<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAset = Barang::sum('stok');
        $unitTersedia = Barang::where('status', 'Tersedia')->sum('stok');
        $unitDipinjam = Barang::whereIn('status', ['Dipinjam', 'Maintenance'])->sum('stok');
        $pendingCount = Peminjaman::where('status_transaksi', 'Pending')->count();

        // Khusus jika siswa login, hitung juga jumlah pengajuan miliknya
        $pinjamanSaya = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status_transaksi', ['Pending', 'Disetujui'])
            ->count();

        return view('dashboard', compact(
            'totalAset',
            'unitTersedia',
            'unitDipinjam',
            'pendingCount',
            'pinjamanSaya'
        ));
    }
}
