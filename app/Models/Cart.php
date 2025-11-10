<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    
    protected $fillable = [
        'id_menu',
        'nama_menu',
        'takaran',
        'harga',
    ];

    protected $casts = [
        'harga' => 'integer',
    ];

    // Relasi ke DetailPesanan
    public function orderDetails()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu');
    }
}