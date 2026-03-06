<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class jawabansiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswas = DB::table('siswa_profiles')->get();
        $ujians = DB::table('ujian')->get();

        foreach ($ujians as $ujian) {
            $ujianSoal = DB::table('ujian_soal')->where('ujian_id', $ujian->id)->get();

            foreach ($ujianSoal as $soal) {
                foreach ($siswas as $siswa) {
                    $point = rand(0, 100);
                    $isCorrect = rand(1, 100) <= 70;
                    $jawaban = $isCorrect ? DB::table('soal')->where('id', $soal->soal_id)->value('jawaban_benar') : Str::random(10);

                    DB::table('jawaban_siswa')->insert([
                        'ujian_id' => $ujian->id,
                        'siswa_id' =>  $siswa->user_id,
                        'soal_id' => $soal->soal_id,
                        'jawaban' => $jawaban,
                        'waktu_submit' => 120,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $bonus = rand(0, 1) ? 5 : 0;
                    $jumlahPoint = 5 + $bonus;

                    DB::table('riwayat_point')->insert([
                        'id_siswa' => $siswa->user_id,
                        'ujian_id' => $ujian->id,
                        'soal_id' => $soal->soal_id,
                        'jumlah_point' => $jumlahPoint,
                        'point_dasar' => 5,
                        'bonus_point' => $bonus,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
