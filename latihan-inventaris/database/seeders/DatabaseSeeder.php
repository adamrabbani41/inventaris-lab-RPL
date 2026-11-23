<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengujian v2 (1 Laboran & 2 Siswa)
        User::create([
            'nis_nip'  => '198501012010011001',
            'name'     => 'Pak Budi (Laboran)',
            'email'    => 'laboran@simlab.test',
            'role'     => 'laboran',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'nis_nip'  => '242510001',
            'name'     => 'Adam (Siswa 1)',
            'email'    => 'adam@simlab.test',
            'role'     => 'siswa',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'nis_nip'  => '242510002',
            'name'     => 'Siti (Siswa 2)',
            'email'    => 'siti@simlab.test',
            'role'     => 'siswa',
            'password' => Hash::make('password'),
        ]);

        // 2. Data Kategori v1
        Kategori::create([
            'nama_kategori' => 'Komputer & Laptop',
            'deskripsi'     => 'Perangkat PC desktop dan laptop praktik'
        ]);

        Kategori::create([
            'nama_kategori' => 'Perangkat Jaringan',
            'deskripsi'     => 'Router, switch, access point, dan modem'
        ]);

        Kategori::create([
            'nama_kategori' => 'Alat Perkabelan',
            'deskripsi'     => 'Tang crimping, LAN tester, kabel UTP, dan RJ45'
        ]);

        Kategori::create([
            'nama_kategori' => 'Multimedia',
            'deskripsi'     => 'Proyektor, layar, kamera, dan audio'
        ]);
    }
}
