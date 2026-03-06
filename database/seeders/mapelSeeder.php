<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;  

class mapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MataPelajaran::insert([
            [
            'id' => 1,
            'kode_mapel' => 'TK001',
            'nama_mapel' => 'Mengenal Huruf',
            'deskripsi' => 'Pelajaran untuk mengenal huruf A-Z',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id' => 2,
            'kode_mapel' => 'TK002',
            'nama_mapel' => 'Mengenal Angka',
            'deskripsi' => 'Pelajaran untuk mengenal angka 1-10',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id' => 3,
            'kode_mapel' => 'TK003',
            'nama_mapel' => 'Mengenal Warna',
            'deskripsi' => 'Pelajaran untuk mengenal warna-warna dasar',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id' => 4,
            'kode_mapel' => 'TK004',
            'nama_mapel' => 'Mengenal Bentuk',
            'deskripsi' => 'Pelajaran untuk mengenal bentuk-bentuk dasar seperti lingkaran, segitiga, dan persegi',
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
