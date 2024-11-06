<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisKompenModel extends Model
{
    use HasFactory;

    protected $table = 'jenis_kompen';
    protected $primaryKey = 'id_jenis_kompen';

    protected $fillable = ['kode_jenis', 'nama_jenis'];

    public function kompen(): HasMany {
        return $this->hasMany(KompenModel::class, 'id_jenis_kompen', 'id_kompen');
    }
}
