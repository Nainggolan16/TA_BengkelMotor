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
        Schema::create('jenis_servis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori_servis')
                ->nullable()
                ->constrained('kategori_servis')
                ->nullOnDelete();

            $table->string('nama_servis', 80);
            $table->decimal('harga_jasa', 10, 2);
            $table->text('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_servis');
    }
};
