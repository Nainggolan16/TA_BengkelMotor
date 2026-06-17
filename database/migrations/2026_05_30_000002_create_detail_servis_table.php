<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_servis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_order')
                ->constrained('order_servis')
                ->cascadeOnDelete();
            $table->foreignId('id_jenis_servis')
                ->constrained('jenis_servis')
                ->cascadeOnDelete();
            $table->decimal('harga_jasa', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_servis');
    }
};
