<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonilAkademikModel extends Model
{
    use HasFactory;

    protected $table = 'personil_akademik';
    protected $primaryKey = 'id_personil';

    protected $fillable = ['id_level', 'nomor_induk', 'username', 'nama', 'nomor_telp'];
    
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function level(): BelongsTo{
        return $this->belongsTo(LevelModel::class, 'id_level', 'id_level');
    }

    public function kompen(): HasMany {
        return $this->hasMany(KompenModel::class, 'id_personil', 'id_personil');
    }
}
