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
        Schema::create('personil_akademik', function (Blueprint $table) {
            $table->id('id_personil');
            $table->string('nomor_induk', 18)->unique();
            $table->string('username', 20);
            $table->string('nama', 255);
            $table->string('password', 255);
            $table->char('nomor_telp', 15)->nullable();
            $table->unsignedBigInteger('id_level')->index();
            $table->timestamps();

            $table->foreign('id_level')->references('id_level')->on('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personil_akademik');
    }
};
