<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'idtransaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'idtransaksi',
        'tanggal_transaksi',
        'total_pesanan',
        'total_harga',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'total_harga' => 'decimal:2',
    ];

    // Relasi ke DetailPesanan
    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'idtransaksi', 'idtransaksi');
    }
}