@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-sm border border-slate-200">
    <h2 class="text-2xl font-bold mb-6">Ubah Data Barang Inventaris</h2>

    {{-- Wajib tambahkan enctype="multipart/form-data" --}}
    <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" class="w-full border border-slate-300 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                @error('kode_barang') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="w-full border border-slate-300 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                @error('nama_barang') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="kategori_id" class="w-full border border-slate-300 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id', $barang->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('kategori_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $barang->stok) }}" class="w-full border border-slate-300 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                @error('stok') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Kondisi</label>
                <select name="kondisi" class="w-full border border-slate-300 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="Baik" {{ old('kondisi', $barang->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ old('kondisi', $barang->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ old('kondisi', $barang->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
                @error('kondisi') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full border border-slate-300 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="Tersedia" {{ old('status', $barang->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Dipinjam" {{ old('status', $barang->status) == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="Maintenance" {{ old('status', $barang->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('status') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Spesifikasi (Opsional)</label>
            <textarea name="spesifikasi" class="w-full border border-slate-300 rounded p-2 focus:ring-2 focus:ring-indigo-500 outline-none" rows="3">{{ old('spesifikasi', $barang->spesifikasi) }}</textarea>
        </div>

        {{-- Pratinjau Foto Lama & Input Ganti Foto Baru (Tambahan v2) --}}
        <div>
            <label class="block text-sm font-medium mb-1">Foto Perangkat (Biarkan kosong jika tidak ingin mengganti)</label>
            @if($barang->foto)
                <div class="mb-2 flex items-center space-x-3">
                    <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto {{ $barang->nama_barang }}" class="w-20 h-20 object-cover rounded border border-slate-300">
                    <span class="text-xs text-slate-500">Foto saat ini</span>
                </div>
            @endif
            <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp" class="w-full border border-slate-300 rounded p-2 text-sm text-slate-600 file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
            @error('foto') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-2 pt-4">
            <a href="{{ route('barang.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded">Batal</a>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded">Perbarui Data</button>
        </div>
    </form>
</div>
@endsection
