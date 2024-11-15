<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MahasiswaModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';

    protected $fillable = ['id_level', 'password' ,'id_prodi', 'nomor_induk', 'username', 'nama', 'semester', 'jam_alpha', 'jam_kompen', 'jam_kompen_selesai', 'created_at', 'updated_at'];
    
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function level(): BelongsTo{
        return $this->belongsTo(LevelModel::class, 'id_level', 'id_level');
    }

    public function prodi(): BelongsTo{
        return $this->belongsTo(ProdiModel::class, 'id_prodi', 'id_prodi');
    }

    public function kompenDetail(): HasMany {
        return $this->hasMany(KompenDetailModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function kompetensiMahasiswa(): HasMany {
        return $this->hasMany(ListKompetensiMahasiswaModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function qrKode(): HasMany {
        return $this->hasMany(QrKodeModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function pengajuanKompen(): HasMany {
        return $this->hasMany(PengajuanKompenModel::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function getRoleName() : string {
        return $this->level->nama_level;
    }

    public function hasRole($role): bool {
        return $this->level->kode_level == $role;
    }

    public function getRole(): string {
        return $this->level->kode_level;
    }
}
