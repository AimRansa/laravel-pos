<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepMenu extends Model
{
    protected $table = 'resep_menu';

    protected $fillable = [
        'id_menu',
        'id_produk',
        'takaran',
        'satuan',
    ];

    public function produk()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Cart::class, 'id_menu', 'id_menu');
    }
}
