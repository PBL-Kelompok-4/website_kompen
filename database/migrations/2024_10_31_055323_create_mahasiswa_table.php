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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->string('nomor_induk', 10)->unique();
            $table->string('username', 20);
            $table->string('nama', 255);
            $table->smallInteger('semester');
            $table->unsignedBigInteger('id_prodi')->index();
            $table->string('password', 255);
            $table->integer('jam_alpha');
            $table->integer('jam_kompen');
            $table->integer('jam_kompen_selesai');
            $table->unsignedBigInteger('id_level')->index()->default(5);
            $table->timestamps();

            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
            $table->foreign('id_level')->references('id_level')->on('level');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
