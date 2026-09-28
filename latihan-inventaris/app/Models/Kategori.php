<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
     protected $guarded = ['id'];

    // Relasi: Satu Kategori memiliki banyak Barang (One-to-Many)
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
