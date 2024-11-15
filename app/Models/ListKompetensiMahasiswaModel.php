<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListKompetensiMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'list_kompetensi_mahasiswa';
    protected $primaryKey = 'id_list_kompetensi_mahasiswa';

    protected $fillable = ['id_mahasiswa', 'id_kompetensi' , 'created_at', 'updated_at'];

    public function mahasiswa(): BelongsTo{
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function kompetensi(): BelongsTo{
        return $this->belongsTo(KompetensiModel::class, 'id_kompetensi', 'id_kompetensi');
    }
}
