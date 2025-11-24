<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanDetail extends Model
{
    protected $table = 'laporan_detail';

    protected $fillable = [
        'laporan_id',
        'id_menu',
        'nama_menu',
        'quantity',
        'subtotal',
    ];
}
