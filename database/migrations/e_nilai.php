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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('guru_id');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->enum('status', ['not_graded', 'graded'])->default('not_graded');
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->foreign('ujian_id')->references('id')->on('ujian')->onDelete('cascade');
            $table->foreign('siswa_id')->references('user_id')->on('siswa_profiles')->onDelete('cascade');
            $table->foreign('guru_id')->references('id')->on('guru_profiles')->onDelete('cascade');
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
