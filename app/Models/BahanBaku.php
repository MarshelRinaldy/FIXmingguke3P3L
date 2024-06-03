<?php

namespace App\Models;

use App\Models\Resep;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanBaku extends Model
{
    use HasFactory;
    protected $table = 'bahan_baku';

    protected $fillable = ['nama_bahan_baku', 'stok_bahan_baku', 'satuan_bahan_baku', 'harga_bahan_baku', 'total_digunakan'];


    public function products()
    {
        return $this->belongsToMany(BahanBaku::class, 'reseps', 'produk_id', 'bahan_baku_id',)
            ->using(Resep::class)
            ->withPivot('jumlah')
            ->withTimestamps();
    }

    public function catatBahanBaku(){
        return $this->hasMany(catatBB::class);
    }

    // resep
    public function resep()
    {
        return $this->hasMany(Resep::class);
    }

}

