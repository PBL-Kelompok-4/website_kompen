<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;


class PersonilAkademikModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'personil_akademik';
    protected $primaryKey = 'id_personil';

    protected $fillable = ['id_level', 'nomor_induk', 'username', 'nama', 'nomor_telp', 'password', 'created_at', 'updated_at'];
    
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function level(): BelongsTo{
        return $this->belongsTo(LevelModel::class, 'id_level', 'id_level');
    }

    public function kompen(): HasMany {
        return $this->hasMany(KompenModel::class, 'id_personil', 'id_personil');
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
