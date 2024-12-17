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
        Schema::create('kompen', function (Blueprint $table) {
            $table->id('id_kompen');
            $table->uuid('nomor_kompen')->unique();
            $table->string('nama', 40);
            $table->string('deskripsi', 255);
            $table->unsignedBigInteger('id_personil')->index();
            $table->unsignedBigInteger('id_jenis_kompen')->index();
            $table->integer('kuota');
            $table->integer('jam_kompen');
            $table->enum('status', ['ditutup', 'dibuka', 'progres'])->default('ditutup');
            $table->enum('is_selesai', ['no', 'yes'])->default('no');
            $table->string('alasan', 255)->nullable();
            $table->enum('status_acceptance', ['pending', 'accept', 'reject'])->default('pending');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->timestamps();

            $table->foreign('id_personil')->references('id_personil')->on('personil_akademik');
            $table->foreign('id_jenis_kompen')->references('id_jenis_kompen')->on('jenis_kompen');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompen');
    }
};
