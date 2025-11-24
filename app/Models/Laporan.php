<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';
    public $timestamps = false;

    protected $fillable = [
        'id_laporan',
        'tanggal',
        'total_transaksi',
        'total_pendapatan'
    ];

    public function detail()
    {
        return $this->hasMany(LaporanDetail::class, 'laporan_id', 'id_laporan');
    }

    public function stok()
    {
        return $this->hasMany(LaporanStok::class, 'laporan_id', 'id_laporan');
    }
}
