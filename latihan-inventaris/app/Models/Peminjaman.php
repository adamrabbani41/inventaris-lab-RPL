<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    protected $guarded = ['id'];

    // Relasi: Setiap Peminjaman dilakukan oleh satu User (Siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Setiap Peminjaman merujuk pada satu Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
