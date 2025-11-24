<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'laporan';          // ✔ tabel yang benar

    protected $primaryKey = 'id_laporan';  // ✔ primary key yang benar

    public $incrementing = true;           // ✔ auto increment (boleh true)

    public $timestamps = false;            // ✔ tabel laporan tidak punya timestamp

    protected $fillable = [
        'tanggal_laporan',
        'jumlah_transaksi',
        'total_stok',
    ];
}
