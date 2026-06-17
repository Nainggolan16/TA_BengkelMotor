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
        Schema::create('stok_masuk', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_suku_cadang')
                ->constrained('suku_cadang')
                ->cascadeOnDelete();

            $table->foreignId('id_pengguna')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->integer('jumlah');
            $table->integer('sisa_stok')->default(0);
            $table->decimal('harga_beli', 10, 2);
            $table->date('tanggal');
            $table->string('catatan', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_masuk');
    }
};
