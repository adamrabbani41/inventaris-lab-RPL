<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategoriId = $request->input('kategori_id');

        // Menggunakan Eloquent Conditional Clause when() & paginate() sesuai teori LKPD Hal. 2
        $barangs = Barang::with('kategori')
            ->when($search, function ($query, $search) {
                $query->where('nama_barang', 'like', "%{$search}%")
                      ->orWhere('kode_barang', 'like', "%{$search}%");
            })
            ->when($kategoriId, function ($query, $kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->orderBy('nama_barang', 'asc')
            ->paginate(6)
            ->withQueryString();

        $kategoris = Kategori::all();

        return view('siswa.katalog', compact('barangs', 'kategoris', 'search', 'kategoriId'));
    }
}
