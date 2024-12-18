<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KompenDetailModel extends Model
{
    use HasFactory;

    protected $table = 'kompen_detail';
    protected $primaryKey = 'id_kompen_detail';

    protected $fillable = ['id_kompen', 'id_mahasiswa', 'progres_1', 'progres_2', 'status', 'bukti_kompen'];

    public function kompen(): BelongsTo{
        return $this->belongsTo(KompenModel::class, 'id_kompen', 'id_kompen');
    }

    public function mahasiswa(): BelongsTo{
        return $this->belongsTo(MahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
