<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_stok', function (Blueprint $table) {
            $table->string('satuan', 50)->nullable()->after('jumlah_berkurang');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_stok', function (Blueprint $table) {
            $table->dropColumn('satuan');
        });
    }
};
