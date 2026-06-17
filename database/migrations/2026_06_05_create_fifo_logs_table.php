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
        Schema::create('fifo_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_order')
                ->constrained('order_servis')
                ->cascadeOnDelete();
            $table->foreignId('id_suku_cadang')
                ->constrained('suku_cadang')
                ->cascadeOnDelete();
            $table->foreignId('id_batch_stok')
                ->constrained('stok_masuk')
                ->cascadeOnDelete();
            $table->integer('qty_dipakai');
            $table->integer('sisa_batch');
            $table->decimal('harga_beli', 10, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fifo_logs');
    }
};
