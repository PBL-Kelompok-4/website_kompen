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
        Schema::table('kompen', function (Blueprint $table) {
            $table->unsignedBigInteger('id_status_acceptance')->after('tanggal_selesai')->default(1)->index();

            $table->foreign('id_status_acceptance')->references('id_status_acceptance')->on('status_acceptance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kompen', function (Blueprint $table) {
            //
        });
    }
};
