<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ujianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruProfiles = DB::table('guru_profiles')->pluck('id')->toArray();
        $ujianData = [
            [
                'kelas_id' => 1,
                'mapel_id' => 1,
                'judul' => 'Ujian Mengenal Huruf',
                'tipe_ujian' => 'magic_card',
                'waktu_mulai' => now(),
                'waktu_selesai' => now()->addHours(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelas_id' => 1,
                'mapel_id' => 2,
                'judul' => 'Ujian Mengenal Angka',
                'tipe_ujian' => 'choose_it',
                'waktu_mulai' => now(),
                'waktu_selesai' => now()->addHours(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelas_id' => 1,
                'mapel_id' => 3,
                'judul' => 'Ujian Mengenal Warna',
                'tipe_ujian' => 'magic_card',
                'waktu_mulai' => now(),
                'waktu_selesai' => now()->addHours(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($ujianData as $index => $ujian) {
            $ujian['guru_id'] = 2;
            DB::table('ujian')->insert($ujian);
        }

        $questions = [
            // Questions for ujian_id 1
            ['id' => 1, 'ujian_id' => 1, 'pertanyaan' => 'Apa huruf pertama dalam alfabet?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'A'],
            ['id' => 2, 'ujian_id' => 1, 'pertanyaan' => 'Apa huruf terakhir dalam alfabet?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'C'],
            ['id' => 3, 'ujian_id' => 1, 'pertanyaan' => 'Huruf apa yang ada di antara A dan C?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'B'],
            ['id' => 4, 'ujian_id' => 1, 'pertanyaan' => 'Huruf vokal pertama dalam alfabet?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'A'],
            ['id' => 5, 'ujian_id' => 1, 'pertanyaan' => 'Huruf vokal terakhir dalam alfabet?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'D'],
            ['id' => 6, 'ujian_id' => 1, 'pertanyaan' => 'Huruf apa yang ada sebelum huruf D?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'E'],
            ['id' => 7, 'ujian_id' => 1, 'pertanyaan' => 'Huruf apa yang ada setelah huruf X?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'A'],
            ['id' => 8, 'ujian_id' => 1, 'pertanyaan' => 'Huruf apa yang ada di tengah alfabet?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'B'],
            ['id' => 9, 'ujian_id' => 1, 'pertanyaan' => 'Huruf apa yang ada sebelum huruf A?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'D'],
            ['id' => 10, 'ujian_id' => 1, 'pertanyaan' => 'Huruf apa yang ada setelah huruf Z?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'A'],

            // Questions for ujian_id 2
            ['id' => 11, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 2 + 2?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'A'],
            ['id' => 12, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 5 - 3?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'B'],
            ['id' => 13, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 10 ÷ 2?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'C'],
            ['id' => 14, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 3 × 3?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'D'],
            ['id' => 15, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 7 + 6?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'E'],
            ['id' => 16, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 8 - 4?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'A'],
            ['id' => 17, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 6 ÷ 3?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'B'],
            ['id' => 18, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 4 x 4?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'C'],
            ['id' => 19, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 9 + 1?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'D'],
            ['id' => 20, 'ujian_id' => 2, 'pertanyaan' => 'Berapa hasil dari 12 - 7?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'E'],

            // Questions for ujian_id 3
            ['id' => 21, 'ujian_id' => 3, 'pertanyaan' => 'Sebutkan warna primer!', 'tipe' => 'essay', 'jawaban_benar' => 'Merah, Biru, Kuning'],
            ['id' => 22, 'ujian_id' => 3, 'pertanyaan' => 'Apa warna langit pada siang hari?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'A'],
            ['id' => 23, 'ujian_id' => 3, 'pertanyaan' => 'Apa warna daun?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'B'],
            ['id' => 24, 'ujian_id' => 3, 'pertanyaan' => 'Apa warna darah?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'C'],
            ['id' => 25, 'ujian_id' => 3, 'pertanyaan' => 'Apa warna matahari saat terbenam?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'D'],
            ['id' => 26, 'ujian_id' => 3, 'pertanyaan' => 'Apa warna awan saat hujan?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'E'],
            ['id' => 27, 'ujian_id' => 3, 'pertanyaan' => 'Apa warna pisang matang?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'A'],
            ['id' => 28, 'ujian_id' => 3, 'pertanyaan' => 'Apa warna laut?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'B'],
            ['id' => 29, 'ujian_id' => 3, 'pertanyaan' => 'Apa warna batu bara?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'C'],
            ['id' => 30, 'ujian_id' => 3, 'pertanyaan' => 'Apa warna susu?', 'tipe' => 'pilihan_ganda', 'jawaban_benar' => 'D'],
            ['id' => 31, 'ujian_id' => 3, 'pertanyaan' => 'Jelaskan proses terjadinya pelangi!', 'tipe' => 'essay', 'jawaban_benar' => 'Pelangi terjadi karena pembiasan cahaya matahari oleh tetesan air di atmosfer.'],
            ['id' => 32, 'ujian_id' => 3, 'pertanyaan' => 'Apa yang dimaksud dengan warna sekunder?', 'tipe' => 'essay', 'jawaban_benar' => 'Warna sekunder adalah warna yang dihasilkan dari pencampuran dua warna primer.'],
            ['id' => 33, 'ujian_id' => 3, 'pertanyaan' => 'Sebutkan tiga warna yang sering digunakan dalam desain!', 'tipe' => 'essay', 'jawaban_benar' => 'Merah, biru, dan hijau.'],
            ['id' => 34, 'ujian_id' => 3, 'pertanyaan' => 'Apa perbedaan antara warna hangat dan warna dingin?', 'tipe' => 'essay', 'jawaban_benar' => 'Warna hangat seperti merah dan kuning memberikan kesan energik, sedangkan warna dingin seperti biru dan hijau memberikan kesan tenang.'],
            ['id' => 35, 'ujian_id' => 3, 'pertanyaan' => 'Mengapa warna hitam sering digunakan dalam desain minimalis?', 'tipe' => 'essay', 'jawaban_benar' => 'Warna hitam memberikan kesan elegan, sederhana, dan modern, sehingga cocok untuk desain minimalis.'],
        ];

        $ujianSoal = [];
        foreach ($questions as $question) {
            $ujianSoal[] = [
                'ujian_id' => $question['ujian_id'],
                'soal_id' => $question['id'],
            ];
        }

        foreach ($questions as &$question) {
            $question['gambar'] = null;
            $question['created_at'] = now();
            $question['updated_at'] = now();
            unset($question['ujian_id']);
        }

        DB::table('soal')->insert($questions);

         // Insert into ujian_soal table
        
        DB::table('ujian_soal')->insert($ujianSoal);

        $opsiSoal = [];
        foreach ($questions as $question) {
            if ($question['tipe'] === 'pilihan_ganda') {
                $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'A', 'is_correct' => $question['jawaban_benar'] === 'A', 'created_at' => now(), 'updated_at' => now()];
                $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'B', 'is_correct' => $question['jawaban_benar'] === 'B', 'created_at' => now(), 'updated_at' => now()];
                $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'C', 'is_correct' => $question['jawaban_benar'] === 'C', 'created_at' => now(), 'updated_at' => now()];
                $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'D', 'is_correct' => $question['jawaban_benar'] === 'D', 'created_at' => now(), 'updated_at' => now()];
                $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'E', 'is_correct' => $question['jawaban_benar'] === 'E', 'created_at' => now(), 'updated_at' => now()];

                // Add incorrect options if the correct answer is not one of A, B, C, or D
                if (!in_array($question['jawaban_benar'], ['A', 'B', 'C', 'D','E'])) {
                    $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'A', 'is_correct' => false, 'created_at' => now(), 'updated_at' => now()];
                    $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'B', 'is_correct' => false, 'created_at' => now(), 'updated_at' => now()];
                    $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'C', 'is_correct' => false, 'created_at' => now(), 'updated_at' => now()];
                    $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'D', 'is_correct' => false, 'created_at' => now(), 'updated_at' => now()];
                    $opsiSoal[] = ['soal_id' => $question['id'], 'soal_id' => $question['id'], 'opsi' => 'E', 'is_correct' => false, 'created_at' => now(), 'updated_at' => now()];
                }
            }
        }

        DB::table('opsi_soals')->insert($opsiSoal);
    }
}
