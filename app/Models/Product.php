<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    // Kolom yang boleh diisi lewat create() / update()
    protected $fillable = [
        'id_produk',
        'tanggal_masuk',
        'tanggal_expired',
        'nama_stok',
        'jumlah_stok',
    ];

    // (Opsional) kalau kamu tidak mau pakai timestamps otomatis
    // public $timestamps = false;
}
