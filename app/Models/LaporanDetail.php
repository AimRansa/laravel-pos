<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanDetail extends Model
{
    protected $table = 'laporan_detail';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'laporan_id',
        'id_menu',
        'nama_menu',
        'quantity',
        'subtotal',
    ];
}
