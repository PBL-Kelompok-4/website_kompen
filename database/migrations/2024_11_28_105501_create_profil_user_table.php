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
        Schema::create('foto_profil', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('id_personil'); // Foreign key column
            $table->string('avatar');
            $table->timestamps();
        
            // Define the foreign key relationship explicitly
            $table->foreign('id_personil')
                  ->references('id_personil') // Referenced column in the personil_akademik table
                  ->on('personil_akademik') // Referenced table
                  ->onDelete('cascade'); // Cascade on delete
        });
    
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_profil');
}
};