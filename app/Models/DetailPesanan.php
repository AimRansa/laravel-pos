<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    
    protected $fillable = [
        'idtransaksi',
        'id_menu',
        'nama_menu',
        'quantity',
        'harga',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'harga' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'idtransaksi', 'idtransaksi');
    }

    // Relasi ke Cart (Menu)
    public function menu()
    {
        return $this->belongsTo(Cart::class, 'id_menu', 'id_menu');
    }
}