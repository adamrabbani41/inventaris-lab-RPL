@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Daftar Inventaris Lab RPL</h2>
    <a href="{{ route('barang.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-md shadow-sm transition">
        + Tambah Barang
    </a>
</div>

@if(session('success'))
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase text-slate-600 tracking-wider">
                <th class="p-4 whitespace-nowrap w-24">Kode Barang</th>
                <th class="p-4 w-20">Foto</th>
                <th class="p-4">Nama Barang</th>
                <th class="p-4">Kategori</th>
                <th class="p-4">Stok</th>
                <th class="p-4">Kondisi</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 text-sm">
            @forelse($barangs as $item)
                <tr class="hover:bg-slate-50">
                    <td class="p-4 font-mono font-semibold text-indigo-600">{{ $item->kode_barang }}</td>
                    <td class="p-4">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_barang }}" class="w-12 h-12 object-cover rounded border border-slate-200">
                        @else
                            <div class="w-12 h-12 bg-slate-100 rounded border border-slate-200 flex items-center justify-center text-[10px] text-slate-400 text-center leading-tight">
                                No Foto
                            </div>
                        @endif
                    </td>
                    <td class="p-4 font-medium">{{ $item->nama_barang }}</td>
                    <td class="p-4 text-slate-600">{{ $item->kategori->nama_kategori }}</td>
                    <td class="p-4 font-semibold">{{ $item->stok }}</td>
                    <td class="p-4">{{ $item->kondisi }}</td>
                    <td class="p-4">
                        @if($item->status == 'Tersedia')
                            <span class="bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full text-xs font-semibold">Tersedia</span>
                        @elseif($item->status == 'Dipinjam')
                            <span class="bg-rose-100 text-rose-800 px-2.5 py-0.5 rounded-full text-xs font-semibold">Dipinjam</span>
                        @else
                            <span class="bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full text-xs font-semibold">Maintenance</span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('barang.edit', $item->id) }}" class="text-amber-600 hover:underline font-medium">Edit</a>
                        <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-6 text-center text-slate-500">
                        Belum ada data inventaris. Silakan tambah data baru.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
