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
        Schema::create('detail_suku_cadang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_order')
                ->constrained('order_servis')
                ->cascadeOnDelete();

            $table->foreignId('id_suku_cadang')
                ->constrained('suku_cadang')
                ->cascadeOnDelete();

            $table->integer('jumlah');

            $table->decimal('harga_jual', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_suku_cadang');
    }
};
