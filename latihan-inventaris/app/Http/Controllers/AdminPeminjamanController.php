<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPeminjamanController extends Controller
{
    // 1. Menampilkan daftar seluruh pengajuan pinjam dan barang yang sedang dipinjam
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'barang.kategori'])
            ->latest()
            ->get();

        return view('admin.peminjaman', compact('peminjamans'));
    }

    // 2. Menyetujui peminjaman & mengurangi stok otomatis via DB::transaction
    public function approve($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);
        $barang = $peminjaman->barang;

        // Validasi status & stok sesuai pseudocode LKPD Hal. 7 dan aturan keamanan Hal. 6
        if ($peminjaman->status_transaksi !== 'Pending' || $barang->stok < $peminjaman->jumlah) {
            return redirect()
                ->route('admin.peminjaman.index')
                ->with('error', 'Gagal menyetujui: Stok barang tidak mencukupi atau transaksi sudah diproses.');
        }

        // Blok DB::transaction menjamin prinsip ACID (Commit atau Rollback bersamaan)
        DB::transaction(function () use ($peminjaman, $barang) {
            // a. Kurangi nilai stok barang
            $barang->decrement('stok', $peminjaman->jumlah);

            // b. Jika stok barang setelah dikurangi == 0, ubah status menjadi 'Dipinjam'
            if ($barang->fresh()->stok <= 0) {
                $barang->update(['status' => 'Dipinjam']);
            }

            // c. Ubah status_transaksi pada tabel peminjamans menjadi 'Disetujui'
            $peminjaman->update([
                'status_transaksi' => 'Disetujui',
            ]);
        });

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman ' . $peminjaman->kode_transaksi . ' disetujui dan stok otomatis berkurang!');
    }

    // 3. Menolak pengajuan peminjaman beserta catatan_laboran
    public function reject(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status_transaksi !== 'Pending') {
            return redirect()
                ->route('admin.peminjaman.index')
                ->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_laboran' => 'required|string|max:500',
        ]);

        $peminjaman->update([
            'status_transaksi' => 'Ditolak',
            'catatan_laboran'  => $request->catatan_laboran,
        ]);

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman ' . $peminjaman->kode_transaksi . ' telah ditolak.');
    }

    // 4. Memproses pengembalian barang & menambah kembali stok otomatis via DB::transaction
    public function returnItem(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);
        $barang = $peminjaman->barang;

        if ($peminjaman->status_transaksi !== 'Disetujui') {
            return redirect()
                ->route('admin.peminjaman.index')
                ->with('error', 'Hanya transaksi berstatus Disetujui yang dapat dikembalikan.');
        }

        DB::transaction(function () use ($peminjaman, $barang, $request) {
            // Tambahkan kembali stok barang sesuai jumlah yang dipinjam
            $barang->increment('stok', $peminjaman->jumlah);

            // Pastikan status barang kembali menjadi 'Tersedia' karena stok > 0
            if ($barang->fresh()->stok > 0 && $barang->status === 'Dipinjam') {
                $barang->update(['status' => 'Tersedia']);
            }

            // Perbarui status transaksi, tanggal aktual, dan catatan kondisi alat
            $peminjaman->update([
                'status_transaksi'       => 'Dikembalikan',
                'tanggal_kembali_aktual' => now()->toDateString(),
                'catatan_laboran'        => $request->catatan_laboran ?? 'Alat kembali dalam kondisi baik.',
            ]);
        });

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Pengembalian ' . $peminjaman->kode_transaksi . ' berhasil dicatat dan stok otomatis bertambah!');
    }
}
