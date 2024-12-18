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
        Schema::create('kompen_detail', function (Blueprint $table) {
            $table->id('id_kompen_detail');
            $table->unsignedBigInteger('id_kompen')->index();
            $table->unsignedBigInteger('id_mahasiswa')->index();
            $table->string('progres_1', 255)->nullable();
            $table->string('progres_2', 255)->nullable();
            $table->enum('status', ['progres', 'ditolak', 'diterima'])->default('progres');
            $table->string('bukti_kompen', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_kompen')->references('id_kompen')->on('kompen');
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompen_detail');
    }
};
