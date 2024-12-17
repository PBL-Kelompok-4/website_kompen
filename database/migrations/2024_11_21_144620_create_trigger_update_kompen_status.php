<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER update_kompen_status_after_detail_insert AFTER INSERT ON kompen_detail FOR EACH ROW
            BEGIN
                DECLARE total_detail_count INT;
                DECLARE kompen_kuota INT;

                -- Untuk Cek total baris di detail_kompen
                SELECT COUNT(*) INTO total_detail_count
                FROM kompen_detail
                WHERE id_kompen = NEW.id_kompen;

                -- Untuk Cek berapa maksimal kuota kompen
                SELECT kuota INTO kompen_kuota
                FROM kompen
                WHERE id_kompen = NEW.id_kompen;

                -- Update status kalau kuota sudah penuh
                IF total_detail_count = kompen_kuota THEN
                    UPDATE kompen
                    SET status = "progres"
                    WHERE id_kompen = NEW.id_kompen;

                    -- Update status pelamar kompen
                    UPDATE pengajuan_kompen
                    SET status = "reject"
                    WHERE id_kompen = NEW.id_kompen AND status = "pending";
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXIST update_kompen_status_after_detail_insert');
    }
};
