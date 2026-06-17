<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_pelanggan')
                ->constrained('pelanggan')
                ->cascadeOnDelete();

            $table->string('plat_nomor', 15)->unique();
            $table->string('merk', 50);
            $table->string('tipe', 50);
            $table->year('tahun');
            $table->string('warna', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
