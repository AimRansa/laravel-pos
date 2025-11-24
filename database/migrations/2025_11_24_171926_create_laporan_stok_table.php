<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('laporan_stok')) {
            Schema::create('laporan_stok', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('laporan_id'); // tanpa FK
                $table->string('nama_produk');
                $table->decimal('jumlah_berkurang', 10, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('laporan_stok');
    }
};
