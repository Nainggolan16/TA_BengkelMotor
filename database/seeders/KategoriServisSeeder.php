<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriServisSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('kategori_servis')->insert([
            ['nama_kategori' => 'Servis Rutin', 'keterangan' => 'Servis berkala dan perawatan rutin', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Tune Up & Mesin', 'keterangan' => 'Perbaikan dan penyetelan mesin', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Rem & Suspensi', 'keterangan' => 'Perbaikan sistem rem dan suspensi', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Kelistrikan', 'keterangan' => 'Perbaikan dan diagnosa sistem kelistrikan', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Body & Aksesoris', 'keterangan' => 'Perbaikan bodi dan pemasangan aksesoris', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Lainnya', 'keterangan' => 'Kategori untuk layanan yang tidak cocok dengan kategori lain', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
