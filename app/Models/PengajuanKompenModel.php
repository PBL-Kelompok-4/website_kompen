<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanKompenModel extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_kompen';
    protected $primaryKey = 'id_pengajuan_kompen';

    protected $fillable = ['id_kompen', 'id_mahasiswa', 'status'];

    public function kompen(): BelongsTo{
        return $this->belongsTo(KompenModel::class, 'id_kompen', 'id_kompen');
    }

    public function mahasiswa(): BelongsTo{
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
