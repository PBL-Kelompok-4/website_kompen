<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeModel extends Model
{
    use HasFactory;

    protected $table = 'periode';
    protected $primaryKey = 'id_periode';

    protected $fillable = ['periode', 'created_at', 'updated_at'];

    public function mahasiswa(): HasMany {
        return $this->hasMany(MahasiswaModel::class, 'id_periode', 'id_periode');
    }
}
