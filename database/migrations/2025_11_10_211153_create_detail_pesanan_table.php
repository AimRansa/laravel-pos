<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cegah error jika tabel sudah ada
        if (Schema::hasTable('detail_pesanan')) {
            return;
        }

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('idtransaksi', 3)->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->string('id_menu', 3)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('nama_menu', 191);
            $table->integer('quantity');
            $table->integer('harga');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('idtransaksi')
                  ->references('idtransaksi')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->foreign('id_menu')
                  ->references('id_menu')
                  ->on('cart')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
