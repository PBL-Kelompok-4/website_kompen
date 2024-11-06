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
        Schema::create('list_kompetensi_kompen', function (Blueprint $table) {
            $table->id('id_list_kompetensi_kompen');
            $table->unsignedBigInteger('id_kompen')->index();
            $table->unsignedBigInteger('id_kompetensi')->index();
            $table->timestamps();

            $table->foreign('id_kompen')->references('id_kompen')->on('kompen');
            $table->foreign('id_kompetensi')->references('id_kompetensi')->on('kompetensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_kompetensi_kompen');
    }
};
