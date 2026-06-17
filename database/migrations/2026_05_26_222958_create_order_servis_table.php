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
        Schema::create('order_servis', function (Blueprint $table) {
            $table->id();

            $table->string('kode_order', 20);

            $table->foreignId('id_kendaraan')
                ->constrained('kendaraan')
                ->cascadeOnDelete();

            $table->foreignId('id_jenis_servis')
                ->nullable()
                ->constrained('jenis_servis')
                ->cascadeOnDelete();

            $table->foreignId('id_pengguna')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('keluhan');
            $table->text('catatan_servis')->nullable();
            $table->enum('status', [
                'menunggu_pemeriksaan',
                'proses_pengerjaan',
                'selesai',
                'sudah_dibayar',
            ])->default('menunggu_pemeriksaan');

            $table->string('metode_pembayaran')->nullable();
            $table->date('tanggal_bayar')->nullable();

            $table->decimal('biaya_jasa', 10, 2);
            $table->decimal('biaya_jasa_tambahan', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2);
            $table->date('tanggal_masuk');
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_servis');
    }
};
