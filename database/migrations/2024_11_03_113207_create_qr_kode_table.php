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
        Schema::create('qr_kode', function (Blueprint $table) {
            $table->id('id_qr_kode');
            $table->unsignedBigInteger('id_kompen')->index();
            $table->unsignedBigInteger('id_mahasiswa')->index();
            $table->string('qr_pemberi_tugas', 26)->unique();
            $table->dateTime('tanggal_qr_pemberi_tugas');
            $table->string('qr_petinggi_jurusan', 26)->unique();
            $table->dateTime('tanggal_qr_petinggi_jurusan');
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
        Schema::dropIfExists('qr_kode');
    }
};
