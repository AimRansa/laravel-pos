<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('id_produk', 3)->unique(); // id produk 3 karakter
            $table->date('tanggal_masuk');            // tanggal barang masuk
            $table->date('tanggal_keluar')->nullable(); // boleh kosong saat belum keluar
            $table->date('tanggal_expired');          // tanggal kadaluarsa
            $table->timestamps();                     // created_at dan updated_at
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
