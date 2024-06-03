<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoPoint extends Model
{
    use HasFactory;

    protected $table = 'promopoints';

    protected $fillable = [
        'user_id',
        'nama',
        'jumlah_point',
        'tanggal_dimulai',
        'tanggal_berakhir',
        'deskripsi'
    ];

    /**
     * Get the user that owns the PromoPoint.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
