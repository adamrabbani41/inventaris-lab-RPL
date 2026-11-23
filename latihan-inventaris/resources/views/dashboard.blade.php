<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard SIMLAB-RPL v2') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        {{-- Banner Sambutan --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800">
                Selamat Datang, {{ Auth::user()->name }}!
            </h3>
            <p class="text-sm text-slate-500 mt-1">
                Anda masuk sebagai <span class="font-semibold uppercase text-indigo-600">{{ Auth::user()->role }}</span>
                (NIS/NIP: {{ Auth::user()->nis_nip }}).
            </p>
        </div>

        {{-- Kartu Ringkasan Metrik Berdasarkan Peran --}}
        @if(Auth::user()->role === 'laboran')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200">
                    <p class="text-xs font-semibold uppercase text-slate-500">Total Unit Aset</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalAset }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200">
                    <p class="text-xs font-semibold uppercase text-emerald-600">Unit Tersedia</p>
                    <p class="text-3xl font-bold text-emerald-700 mt-2">{{ $unitTersedia }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200">
                    <p class="text-xs font-semibold uppercase text-rose-600">Dipinjam / Maintenance</p>
                    <p class="text-3xl font-bold text-rose-700 mt-2">{{ $unitDipinjam }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200">
                    <p class="text-xs font-semibold uppercase text-amber-600">Pengajuan Pending</p>
                    <p class="text-3xl font-bold text-amber-700 mt-2">{{ $pendingCount }}</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200">
                    <p class="text-xs font-semibold uppercase text-emerald-600">Alat Tersedia di Lab</p>
                    <p class="text-3xl font-bold text-emerald-700 mt-2">{{ $unitTersedia }} Unit</p>
                    <a href="{{ route('katalog.index') }}" class="inline-block mt-3 text-xs font-semibold text-indigo-600 hover:underline">
                        Lihat Katalog & Ajukan Pinjam →
                    </a>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200">
                    <p class="text-xs font-semibold uppercase text-amber-600">Peminjaman Aktif / Pending Saya</p>
                    <p class="text-3xl font-bold text-amber-700 mt-2">{{ $pinjamanSaya }} Transaksi</p>
                    <a href="{{ route('peminjaman.riwayat') }}" class="inline-block mt-3 text-xs font-semibold text-indigo-600 hover:underline">
                        Cek Status Peminjaman →
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
