<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    // 1. Dashboard metrik jumlah aset & ketersediaan
    public function dashboard()
    {
        $totalAset = Barang::sum('stok');
        $unitTersedia = Barang::where('status', 'Tersedia')->sum('stok');
        $unitDipinjam = Barang::whereIn('status', ['Dipinjam', 'Maintenance'])->sum('stok');

        return view('dashboard', compact('totalAset', 'unitTersedia', 'unitDipinjam'));
    }

    // 2. Menampilkan tabel data seluruh inventaris lab
    public function index()
    {
        $barangs = Barang::with('kategori')->latest()->get();
        return view('barang.index', compact('barangs'));
    }

    // 3. Menampilkan formulir input barang baru & dropdown kategori
    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    // 4. Memvalidasi input lalu menyimpan rekaman ke MySQL
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:20|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|min:3|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'spesifikasi' => 'nullable|string',
            'stok'        => 'required|integer|min:0',
            'kondisi'     => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status'      => 'required|in:Tersedia,Dipinjam,Maintenance',
        ]);

        Barang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan!');
    }

    // 5. Menampilkan formulir ubah data terisi otomatis
    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    // 6. Memvalidasi data ubah lalu memperbarui rekaman di MySQL
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => [
                'required',
                'string',
                'max:20',
                Rule::unique('barangs')->ignore($barang->id),
            ],
            'nama_barang' => 'required|string|min:3|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'spesifikasi' => 'nullable|string',
            'stok'        => 'required|integer|min:0',
            'kondisi'     => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status'      => 'required|in:Tersedia,Dipinjam,Maintenance',
        ]);

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    // 7. Menghapus data barang
    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus!');
    }
}