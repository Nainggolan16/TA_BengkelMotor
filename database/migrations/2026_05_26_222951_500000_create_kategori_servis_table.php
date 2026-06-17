<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_servis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        DB::table('kategori_servis')->insert([
            ['nama_kategori' => 'Servis Ringan', 'keterangan' => 'Perawatan rutin dan servis ringan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Servis Berat', 'keterangan' => 'Perbaikan komponen utama dan overhaul', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Perawatan Berkala', 'keterangan' => 'Perawatan berkala dan pemeriksaan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_servis');
    }
};
