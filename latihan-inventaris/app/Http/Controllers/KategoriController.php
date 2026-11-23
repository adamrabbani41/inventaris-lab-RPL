<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $kategoris = Kategori::orderBy('id', 'asc')->get();

        return view('kategori.index', compact('kategoris'));
    }

    // Menampilkan form tambah kategori
    public function create()
    {
        return view('kategori.create');
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|min:2|max:100|unique:kategoris,nama_kategori',
            'deskripsi' => 'nullable|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Kategori tersebut sudah ada.',
            'nama_kategori.min' => 'Nama kategori minimal 2 karakter.',
        ]);

        Kategori::create($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Menghapus kategori
    public function destroy(Kategori $kategori)
    {
    // Cek apakah kategori masih digunakan barang
    if ($kategori->barangs()->exists()) {
        return redirect()
            ->route('kategori.index')
            ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh barang.');
    }

    $kategori->delete();

    return redirect()
        ->route('kategori.index')
        ->with('success', 'Kategori berhasil dihapus!');
    }

    // Menampilkan form edit kategori
    public function edit(Kategori $kategori)
    {
    return view('kategori.edit', compact('kategori'));
    }

    // Memperbarui kategori
    public function update(Request $request, Kategori $kategori)
    {
    $validated = $request->validate([
        'nama_kategori' => [
            'required',
            'string',
            'min:2',
            'max:100',
            \Illuminate\Validation\Rule::unique('kategoris', 'nama_kategori')
                ->ignore($kategori->id),
        ],
        'deskripsi' => 'nullable|string|max:255',
    ]);

    $kategori->update($validated);

    return redirect()
        ->route('kategori.index')
        ->with('success', 'Kategori berhasil diperbarui!');
    }
}
