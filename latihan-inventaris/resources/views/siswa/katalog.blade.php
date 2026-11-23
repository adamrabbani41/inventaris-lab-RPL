<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Perangkat Lab RPL') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        {{-- Tampilkan Pesan Validasi Error Jika Pengajuan Ditolak --}}
        @if ($errors->any())
            <div class="bg-rose-100 border border-rose-400 text-rose-800 px-4 py-3 rounded">
                <p class="font-bold text-sm">Pengajuan Gagal:</p>
                <ul class="list-disc list-inside text-xs mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Pencarian & Filter Kategori --}}
        <form action="{{ route('katalog.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow-sm border border-slate-200 flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode perangkat..." class="flex-1 border border-slate-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">

            <select name="kategori_id" class="border border-slate-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ $kategoriId == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded">
                    Cari / Filter
                </button>
                @if($search || $kategoriId)
                    <a href="{{ route('katalog.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-medium px-4 py-2 rounded">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Grid Kartu Katalog Sesuai Spesifikasi LKPD Halaman 6 --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($barangs as $item)
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden flex flex-col justify-between">
                    <div>
                        {{-- Foto Perangkat --}}
                        <div class="h-44 bg-slate-100 flex items-center justify-center overflow-hidden border-b border-slate-200">
                            @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_barang }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-slate-400 text-xs">Tidak ada foto perangkat</span>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="flex justify-between items-start gap-2">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-indigo-50 text-indigo-700">
                                    {{ $item->kategori->nama_kategori }}
                                </span>
                                <span class="text-xs font-mono text-slate-400">#{{ $item->kode_barang }}</span>
                            </div>

                            <h3 class="font-bold text-lg text-slate-800 mt-2">{{ $item->nama_barang }}</h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                {{ $item->spesifikasi ?? 'Tidak ada spesifikasi tambahan.' }}
                            </p>

                            <div class="mt-3 flex items-center justify-between text-sm">
                                <span class="text-slate-600">Sisa Stok:</span>
                                <span class="font-bold {{ $item->stok > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $item->stok }} Unit
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Pengajuan Pinjam Langsung di Kartu --}}
                    <div class="p-4 bg-slate-50 border-t border-slate-200">
                        @if($item->stok > 0)
                            <form action="{{ route('peminjaman.store') }}" method="POST" class="space-y-2">
                                @csrf
                                <input type="hidden" name="barang_id" value="{{ $item->id }}">

                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div>
                                        <label class="block text-slate-600 mb-0.5">Jumlah</label>
                                        <input type="number" name="jumlah" min="1" max="{{ $item->stok }}" value="1" required class="w-full border border-slate-300 rounded px-2 py-1">
                                    </div>
                                    <div>
                                        <label class="block text-slate-600 mb-0.5">Tgl Pinjam</label>
                                        <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required class="w-full border border-slate-300 rounded px-1.5 py-1">
                                    </div>
                                    <div>
                                        <label class="block text-slate-600 mb-0.5">Tgl Kembali</label>
                                        <input type="date" name="tanggal_kembali_rencana" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required class="w-full border border-slate-300 rounded px-1.5 py-1">
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold py-2 rounded transition">
                                    Ajukan Pinjam
                                </button>
                            </form>
                        @else
                            <button type="button" disabled class="w-full bg-slate-300 text-slate-500 text-xs font-semibold py-2 rounded cursor-not-allowed">
                                Stok Habis (Tidak Tersedia)
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-8 rounded-lg border border-slate-200 text-center text-slate-500">
                    Perangkat tidak ditemukan sesuai pencarian Anda.
                </div>
            @endforelse
        </div>

        {{-- Navigasi Paginasi --}}
        <div>
            {{ $barangs->links() }}
        </div>
    </div>
</x-app-layout>
