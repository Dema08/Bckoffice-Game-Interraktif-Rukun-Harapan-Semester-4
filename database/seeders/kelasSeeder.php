<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class kelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            [
            'id' => 1,
            'nama_kelas' => 'Kelas TK A',
            'tahun_ajaran' => '2023/2024',
            'semester' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id' => 2,
            'nama_kelas' => 'Kelas TK B',
            'tahun_ajaran' => '2023/2024',
            'semester' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id' => 3,
            'nama_kelas' => 'Kelas PAUD 1',
            'tahun_ajaran' => '2023/2024',
            'semester' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'id' => 4,
            'nama_kelas' => 'Kelas PAUD 2',
            'tahun_ajaran' => '2023/2024',
            'semester' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
