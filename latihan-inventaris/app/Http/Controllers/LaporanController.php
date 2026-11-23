<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function cetakPdf()
    {
        $peminjamans = Peminjaman::with(['user', 'barang.kategori'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.laporan_pdf', compact('peminjamans'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Peminjaman-SIMLAB-RPL-' . date('Y-m-d') . '.pdf');
    }
}
