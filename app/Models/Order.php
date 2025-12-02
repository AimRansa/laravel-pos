<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'idtransaksi';
    public $incrementing = false;
    protected $keyType = 'string';

    // Karena tabel orders TIDAK memiliki created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'idtransaksi',
        'tanggal_transaksi',
        'total_pesanan',
        'total_harga',
        'upload_at',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'upload_at' => 'datetime',
        'total_harga' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'idtransaksi', 'idtransaksi');
    }
}
