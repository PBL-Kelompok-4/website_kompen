<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KompetensiModel extends Model
{
    use HasFactory;
    protected $table = 'kompetensi';
    protected $primaryKey = 'id_kompetensi';

    protected $fillable = ['nama_kompetensi', 'deskripsi_kompetensi'];

    public function kompetensiMahasiswa(): HasMany {
        return $this->hasMany(ListKompetensiMahasiswaModel::class, 'id_kompetensi', 'id_kompetensi');
    }

    public function kompetensiKompen(): HasMany {
        return $this->hasMany(ListKompetensiKompenModel::class, 'id_kompetensi', 'id_kompetensi');
    }
}
