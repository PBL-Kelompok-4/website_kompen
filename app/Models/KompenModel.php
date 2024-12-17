<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KompenModel extends Model
{
    use HasFactory;

    protected $table = 'kompen';
    protected $primaryKey = 'id_kompen';

    protected $fillable = ['id_personil', 'id_jenis_kompen', 'nomor_kompen', 'nama', 'deskripsi', 'kuota', 'jam_kompen', 'status', 'is_selesai','status_acceptance', 'tanggal_mulai', 'tanggal_selesai', 'alasan' ,'created_at', 'updated_at'];

    public function personilAkademik(): BelongsTo{
        return $this->belongsTo(PersonilAkademikModel::class, 'id_personil', 'id_personil');
    }

    public function jenisKompen(): BelongsTo{
        return $this->belongsTo(JenisKompenModel::class, 'id_jenis_kompen', 'id_jenis_kompen');
    }

    public function kompenDetail(): HasMany {
        return $this->hasMany(KompenDetailModel::class, 'id_kompen', 'id_kompen');
    }

    public function qrKode(): HasMany {
        return $this->hasMany(QrKodeModel::class, 'id_kompen', 'id_kompen');
    }

    public function pengajuanKompen(): HasMany {
        return $this->hasMany(PengajuanKompenModel::class, 'id_kompen', 'id_kompen');
    }

    public function kompetensiKompen(): HasMany {
        return $this->hasMany(ListKompetensiKompenModel::class, 'id_kompen', 'id_kompen');
    }
}
