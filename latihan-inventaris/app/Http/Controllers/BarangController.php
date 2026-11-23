<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    // 1. Menampilkan halaman Barang
    public function index()
    {
        $barangs = Barang::with('kategori')
            ->orderByRaw('CAST(kode_barang AS INTEGER) ASC')
            ->get();

        return view('barang.index', compact('barangs'));
    }

    // 2. Menampilkan halaman Data Inventaris (jika masih dipakai di v1)
    public function inventaris()
    {
        $barangs = Barang::with('kategori')
            ->orderByRaw('CAST(kode_barang AS INTEGER) ASC')
            ->get();

        return view('inventaris.index', compact('barangs'));
    }

    // 3. Menampilkan formulir tambah barang
    public function create()
    {
        $kategoris = Kategori::all();

        $kodeTerpakai = Barang::pluck('kode_barang')
            ->map(fn ($kode) => (int) $kode)
            ->toArray();

        $kodeBaru = 1;

        while (in_array($kodeBaru, $kodeTerpakai)) {
            $kodeBaru++;
        }

        return view('barang.create', compact(
            'kategoris',
            'kodeBaru'
        ));
    }

    // 4. Memvalidasi dan menyimpan barang baru (+ Unggah Foto v2)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => [
                'required',
                'string',
                'max:20',
                'regex:/^\S+$/',
                'unique:barangs,kode_barang',
            ],
            'nama_barang' => 'required|string|min:3|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'spesifikasi' => 'nullable|string',
            'stok'        => 'required|integer|min:0',
            'kondisi'     => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status'      => 'required|in:Tersedia,Dipinjam,Maintenance',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kode_barang.regex' => 'Kode barang tidak boleh mengandung spasi.',
            'foto.image'        => 'Berkas harus berupa gambar.',
            'foto.mimes'        => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        // Simpan file foto ke storage/app/public/barangs jika diunggah
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('barangs', 'public');
        }

        Barang::create($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil ditambahkan!');
    }

    // 5. Menampilkan formulir edit barang
    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();

        return view('barang.edit', compact('barang', 'kategoris'));
    }

    // 6. Memvalidasi dan memperbarui barang (+ Ganti & Hapus Foto Lama v2)
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang' => [
                'required',
                'regex:/^\S+$/',
                'string',
                'max:20',
                Rule::unique('barangs', 'kode_barang')->ignore($barang->id),
            ],
            'nama_barang' => 'required|string|min:3|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'spesifikasi' => 'nullable|string',
            'stok'        => 'required|integer|min:0',
            'kondisi'     => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status'      => 'required|in:Tersedia,Dipinjam,Maintenance',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kode_barang.regex' => 'Kode barang tidak boleh mengandung spasi.',
            'foto.image'        => 'Berkas harus berupa gambar.',
            'foto.mimes'        => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        // Jika mengunggah foto baru, hapus foto lama terlebih dahulu
        if ($request->hasFile('foto')) {
            if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
                Storage::disk('public')->delete($barang->foto);
            }
            $validated['foto'] = $request->file('foto')->store('barangs', 'public');
        }

        $barang->update($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    // 7. Menghapus barang (+ Hapus Berkas Foto dari Storage v2)
    public function destroy(Barang $barang)
    {
        if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
            Storage::disk('public')->delete($barang->foto);
        }

        $barang->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil dihapus!');
    }
}
