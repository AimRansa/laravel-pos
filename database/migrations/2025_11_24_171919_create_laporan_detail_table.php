<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('laporan_detail')) {
            Schema::create('laporan_detail', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('laporan_id'); // tanpa FK
                $table->string('id_menu', 10);
                $table->string('nama_menu');
                $table->integer('quantity')->default(0);
                $table->integer('subtotal')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('laporan_detail');
    }
};
