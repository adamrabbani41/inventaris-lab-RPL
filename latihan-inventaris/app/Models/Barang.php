<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $guarded = ['id'];

    // Relasi: Setiap Barang milik satu Kategori (Belongs-To)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi v2: Satu Barang bisa memiliki banyak transaksi Peminjaman (Has-Many)
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
