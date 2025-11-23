<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resep_menu', function (Blueprint $table) {
            $table->id();

            // Menu
            $table->string('id_menu');

            // Bahan dari table products
            $table->unsignedBigInteger('id_produk'); 
            
            $table->decimal('takaran', 10, 2);
            $table->string('satuan');

            $table->timestamps();

            // Relasi ke table cart (menu)
            $table->foreign('id_menu')
                  ->references('id_menu')
                  ->on('cart')
                  ->onDelete('cascade');

            // Relasi ke table products
            $table->foreign('id_produk')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resep_menu');
    }
};
