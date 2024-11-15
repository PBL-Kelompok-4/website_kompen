<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListKompetensiKompenModel extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'list_kompetensi_kompen';
    protected $primaryKey = 'id_list_kompetensi_kompen';

    protected $fillable = ['id_kompen', 'id_kompetensi' , 'created_at', 'updated_at'];

    public function kompen(): BelongsTo{
        return $this->belongsTo(KompenModel::class, 'id_kompen', 'id_kompen');
    }

    public function kompetensi(): BelongsTo{
        return $this->belongsTo(KompetensiModel::class, 'id_kompetensi', 'id_kompetensi');
    }
}
