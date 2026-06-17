<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_jasa_tambahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_order')->constrained('order_servis')->cascadeOnDelete();
            $table->string('nama_jasa', 255);
            $table->decimal('biaya', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_jasa_tambahan');
    }
};
