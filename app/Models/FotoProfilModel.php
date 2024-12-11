<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FotoProfilModel extends Model
{
    protected $table = 'foto_profil';
    protected $fillable = ['id_personil', 'avatar'];

    public function personilAkademik()
    {
        return $this->belongsTo(PersonilAkademikModel::class, 'id_personil');
    }
}