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
}
