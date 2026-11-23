<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Peminjaman Saya') }}
        </h2>
    </x-slot>

    <div class="space-y-4">
        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase text-slate-600">
                        <th class="p-4">Kode Transaksi</th>
                        <th class="p-4">Perangkat</th>
                        <th class="p-4">Jumlah</th>
                        <th class="p-4">Tgl Pinjam</th>
                        <th class="p-4">Rencana Kembali</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Catatan Laboran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse($peminjamans as $trx)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-mono font-semibold text-indigo-600">{{ $trx->kode_transaksi }}</td>
                            <td class="p-4 font-medium">{{ $trx->barang->nama_barang }}</td>
                            <td class="p-4">{{ $trx->jumlah }} Unit</td>
                            <td class="p-4">{{ $trx->tanggal_pinjam }}</td>
                            <td class="p-4">{{ $trx->tanggal_kembali_rencana }}</td>
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
                            </td>
                            <td class="p-4 text-xs text-slate-500">{{ $trx->catatan_laboran ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-500">
                                Anda belum memiliki riwayat peminjaman perangkat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
