<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'idtransaksi';
    public $incrementing = false; // karena idtransaksi VARCHAR(3)
    protected $keyType = 'string';

    protected $fillable = [
        'idtransaksi',
        'tanggal_transaksi',
        'total_pesanan',
        'total_harga',
    ];
}
