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
        Schema::create('nota_pembayaran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_order')
                ->unique()
                ->constrained('order_servis')
                ->cascadeOnDelete();

            $table->foreignId('id_pengguna')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('total', 12, 2);

            $table->enum('metode_bayar', [
                'tunai',
                'transfer'
            ]);

            $table->enum('status_bayar', [
                'belum_lunas',
                'lunas'
            ]);

            $table->timestamp('dibayar_pada')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_pembayaran');
    }
};
