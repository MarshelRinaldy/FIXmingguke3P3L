<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBakuUsage extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku_usage';

    protected $fillable = [
        'bahan_baku_id',
        'transaksi_id',
        'tanggal_transaksi',
        'jumlah_digunakan',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
