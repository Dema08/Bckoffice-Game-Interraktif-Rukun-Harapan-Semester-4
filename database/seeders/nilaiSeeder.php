<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class nilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswas = DB::table('siswa_profiles')->get();
        $ujians = DB::table('ujian')->get();

        foreach ($ujians as $ujian) {
            foreach ($siswas as $siswa) {
                // Ambil semua jawaban siswa untuk ujian ini
                $jawabanSiswa = DB::table('jawaban_siswa')
                    ->where('ujian_id', $ujian->id)
                    ->where('siswa_id', $siswa->user_id)
                    ->get();

                $totalSoal = $jawabanSiswa->count();
                $benar = 0;

                foreach ($jawabanSiswa as $jawaban) {
                    $jawabanBenar = DB::table('soal')->where('id', $jawaban->soal_id)->value('jawaban_benar');
                    if ($jawaban->jawaban == $jawabanBenar) {
                        $benar++;
                    }
                }

                // Hitung nilai: (jumlah benar / total soal) * 100
                $nilai = $totalSoal > 0 ? round(($benar / $totalSoal) * 100, 2) : null;

                // Ambil guru_id dari tabel ujian, jika ada
                $guru_id = $ujian->guru_id ?? DB::table('guru_profiles')->inRandomOrder()->value('id');

                DB::table('nilai')->insert([
                    'ujian_id' => $ujian->id,
                    'siswa_id' => $siswa->user_id,
                    'guru_id' => $guru_id,
                    'nilai' => $nilai,
                    'status' => 'graded',
                    'feedback' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
