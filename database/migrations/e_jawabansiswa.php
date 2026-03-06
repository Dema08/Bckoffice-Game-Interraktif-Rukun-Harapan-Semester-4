<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('soal_id');
            $table->text('jawaban');
            $table->integer('waktu_submit')->nullable();
            $table->boolean('is_jawaban_benar')->default(false);
            $table->timestamps();

            $table->foreign('ujian_id')->references('id')->on('ujian')->onDelete('cascade');
            $table->foreign('siswa_id')->references('user_id')->on('siswa_profiles')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soal')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
