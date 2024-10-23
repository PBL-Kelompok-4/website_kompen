<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang'; // Mendefinisikan nama tabel
    protected $primaryKey = 'barang_id'; // Mendefinisikan primary key

    protected $fillable = ['kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual'];

    public function kategori(): BelongsTo{
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    public function stok(): HasMany {
        return $this->hasMany(StokModel::class, 'barang_id', 'barang_id');
    }

    // public function penjualan_detail(): HasMany {
    //     return $this->hasMany(PenjualanDetailModel::class, 'barang_id', 'barang_id');
    // }
}
