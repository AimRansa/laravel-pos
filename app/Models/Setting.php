<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Hubungkan ke tabel laporan
    protected $table = 'laporan';

    // Primary key
    protected $primaryKey = 'id';

    // Jika ID tidak auto increment, bisa diatur manual
    public $incrementing = true;

    // Kalau tidak pakai timestamps (created_at, updated_at), ubah ke false
    public $timestamps = true;

    // Kolom yang bisa diisi
    protected $fillable = [
        'id_laporan',
        'jumlah_transaksi',
        'tanggal_laporan',
        'total_stok',
    ];
}
