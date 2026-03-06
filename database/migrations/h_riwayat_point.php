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
        Schema::create('riwayat_point', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('soal_id');
            $table->integer('jumlah_point')->default(0);
            $table->integer('point_dasar')->default(0);
            $table->integer('bonus_point')->default(0);
            $table->timestamps();

            $table->foreign('id_siswa')->references('user_id')->on('siswa_profiles')->onDelete('cascade');
            $table->foreign('ujian_id')->references('id')->on('ujian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_point');
    }
};
