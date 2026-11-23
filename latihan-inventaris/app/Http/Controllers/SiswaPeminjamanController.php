<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaPeminjamanController extends Controller
{
    // Menampilkan riwayat peminjaman milik siswa yang sedang login
    public function index()
    {
        $peminjamans = Peminjaman::with('barang.kategori')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('siswa.riwayat', compact('peminjamans'));
    }

    // Menyimpan pengajuan peminjaman baru
    public function store(Request $request)
    {
        $barang = Barang::findOrFail($request->barang_id);

        $validated = $request->validate([
            'barang_id'               => 'required|exists:barangs,id',
            'jumlah'                  => 'required|integer|min:1|max:' . $barang->stok,
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
        ], [
            'jumlah.max'                            => 'Jumlah pinjam melebihi stok tersedia (' . $barang->stok . ' unit).',
            'tanggal_pinjam.after_or_equal'         => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'tanggal_kembali_rencana.after_or_equal'=> 'Tanggal rencana kembali harus sama atau setelah tanggal pinjam.',
        ]);

        // Generate kode transaksi unik otomatis (Contoh: TRX-20261123-001)
        $tanggalKode = now()->format('Ymd');
        $urutan = Peminjaman::whereDate('created_at', today())->count() + 1;
        $kodeTransaksi = 'TRX-' . $tanggalKode . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        Peminjaman::create([
            'kode_transaksi'          => $kodeTransaksi,
            'user_id'                 => Auth::id(),
            'barang_id'               => $barang->id,
            'jumlah'                  => $validated['jumlah'],
            'tanggal_pinjam'          => $validated['tanggal_pinjam'],
            'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
            'status_transaksi'        => 'Pending',
        ]);

        return redirect()
            ->route('peminjaman.riwayat')
            ->with('success', 'Pengajuan peminjaman (' . $kodeTransaksi . ') berhasil dikirim! Menunggu persetujuan Laboran.');
    }
}
