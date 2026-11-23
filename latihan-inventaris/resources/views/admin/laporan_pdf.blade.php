<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman SIMLAB-RPL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #475569;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #94a3b8;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .footer {
            margin-top: 24px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekapitulasi Transaksi Peminjaman & Inventaris Lab RPL</h2>
        <p>Sistem Informasi Manajemen Laboratorium (SIMLAB-RPL v2) | Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>NIS / Nama Peminjam</th>
                <th>Nama Perangkat</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Aktual Kembali</th>
                <th>Status</th>
                <th>Catatan Laboran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $trx)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $trx->kode_transaksi }}</td>
                    <td>{{ $trx->user->nis_nip }} - {{ $trx->user->name }}</td>
                    <td>{{ $trx->barang->nama_barang }}</td>
                    <td>{{ $trx->jumlah }} Unit</td>
                    <td>{{ $trx->tanggal_pinjam }}</td>
                    <td>{{ $trx->tanggal_kembali_rencana }}</td>
                    <td>{{ $trx->tanggal_kembali_aktual ?? '-' }}</td>
                    <td>{{ $trx->status_transaksi }}</td>
                    <td>{{ $trx->catatan_laboran ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">Belum ada data transaksi peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Bandung, {{ now()->format('d F Y') }}</p>
        <p>Petugas Laboratorium RPL,</p>
        <br><br><br>
        <p><strong>( _______________________ )</strong></p>
    </div>
</body>
</html>
