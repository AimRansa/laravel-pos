<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanStok extends Model
{
    protected $table = 'laporan_stok';
    protected $primaryKey = 'id_stok';
    public $timestamps = false;

    protected $fillable = [
        'laporan_id',
        'nama_produk',
        'jumlah_berkurang',
        'satuan',
    ];
}
