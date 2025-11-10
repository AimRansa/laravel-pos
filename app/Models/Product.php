<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_produk',
        'nama_stok',
        'jumlah_stok',
        'satuan',
        'tanggal_masuk',
        'tanggal_expired',
    ];

    // PENTING: Cast tanggal ke Carbon instance
    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_expired' => 'date',
        'jumlah_stok' => 'integer',
    ];
}