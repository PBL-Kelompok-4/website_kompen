<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrKodeModel extends Model
{
    use HasFactory;

    protected $table = 'qr_kode';
    protected $primaryKey = 'id_qr_kode';

    protected $fillable = ['id_kompen', 'id_mahasiswa', 'qr_code'];

    public function kompen(): BelongsTo{
        return $this->belongsTo(KompenModel::class, 'id_kompen', 'id_kompen');
    }

    public function mahasiswa(): BelongsTo{
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
