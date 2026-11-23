<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Transaksi Peminjaman Lab') }}
            </h2>
            {{-- Tombol Cetak Laporan PDF (Pertemuan 4) --}}
            <a href="{{ route('admin.laporan.pdf') }}" class="inline-flex items-center bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2 rounded shadow-sm transition">
                Cetak Laporan PDF
            </a>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-100 border border-rose-400 text-rose-800 px-4 py-3 rounded text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase text-slate-600">
                        <th class="p-4">Kode</th>
                        <th class="p-4">Peminjam (Siswa)</th>
                        <th class="p-4">Perangkat</th>
                        <th class="p-4">Jml</th>
                        <th class="p-4">Jadwal Pinjam</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Aksi Laboran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse($peminjamans as $trx)
                        <tr class="hover:bg-slate-50 align-top">
                            <td class="p-4 font-mono font-semibold text-indigo-600 whitespace-nowrap">
                                {{ $trx->kode_transaksi }}
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-slate-800">{{ $trx->user->name }}</div>
                                <div class="text-xs text-slate-500">NIS: {{ $trx->user->nis_nip }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-medium">{{ $trx->barang->nama_barang }}</div>
                                <div class="text-xs text-slate-500">Sisa Stok: {{ $trx->barang->stok }}</div>
                            </td>
                            <td class="p-4 font-semibold">{{ $trx->jumlah }}</td>
                            <td class="p-4 text-xs space-y-1 whitespace-nowrap">
                                <div><span class="text-slate-500">Pinjam:</span> {{ $trx->tanggal_pinjam }}</div>
                                <div><span class="text-slate-500">Rencana:</span> {{ $trx->tanggal_kembali_rencana }}</div>
                                @if($trx->tanggal_kembali_aktual)
                                    <div class="text-emerald-700 font-semibold">Aktual: {{ $trx->tanggal_kembali_aktual }}</div>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($trx->status_transaksi === 'Pending')
                                    <span class="bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full text-xs font-semibold">Pending</span>
                                @elseif($trx->status_transaksi === 'Disetujui')
                                    <span class="bg-sky-100 text-sky-800 px-2.5 py-0.5 rounded-full text-xs font-semibold">Disetujui</span>
                                @elseif($trx->status_transaksi === 'Dikembalikan')
                                    <span class="bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full text-xs font-semibold">Dikembalikan</span>
                                @else
                                    <span class="bg-rose-100 text-rose-800 px-2.5 py-0.5 rounded-full text-xs font-semibold">Ditolak</span>
                                @endif

                                @if($trx->catatan_laboran)
                                    <p class="text-xs text-slate-500 mt-1 italic">"{{ $trx->catatan_laboran }}"</p>
                                @endif
                            </td>
                            <td class="p-4 space-y-2 min-w-[210px]">
                                @if($trx->status_transaksi === 'Pending')
                                    {{-- Tombol Setujui --}}
                                    <form action="{{ route('admin.peminjaman.approve', $trx->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-1.5 px-3 rounded">
                                            ✓ Setujui Peminjaman
                                        </button>
                                    </form>

                                    {{-- Form Tolak dengan Alasan --}}
                                    <form action="{{ route('admin.peminjaman.reject', $trx->id) }}" method="POST" class="flex gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="catatan_laboran" placeholder="Alasan penolakan..." required class="w-full border border-slate-300 rounded px-2 py-1 text-xs">
                                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-2.5 py-1 rounded">
                                            Tolak
                                        </button>
                                    </form>

                                @elseif($trx->status_transaksi === 'Disetujui')
                                    {{-- Form Pengembalian Alat --}}
                                    <form action="{{ route('admin.peminjaman.return', $trx->id) }}" method="POST" class="space-y-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="catatan_laboran" placeholder="Catatan kondisi alat..." class="w-full border border-slate-300 rounded px-2 py-1 text-xs">
                                        <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold py-1.5 px-3 rounded">
                                            ↺ Proses Kembalikan Alat
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Selesai diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-500">
                                Belum ada transaksi peminjaman yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
