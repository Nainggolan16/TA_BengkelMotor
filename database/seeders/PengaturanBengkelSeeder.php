<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanBengkelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['key' => 'nama_bengkel', 'value' => 'Bengkel Motor Jaya'],
            ['key' => 'alamat', 'value' => 'Jl. Contoh No. 1, Semarang'],
            ['key' => 'no_telepon', 'value' => '08123456789'],
            ['key' => 'jam_operasional', 'value' => '08.00 - 17.00 WIB'],
            ['key' => 'catatan_nota', 'value' => 'Terima kasih telah mempercayakan kendaraan Anda kepada kami.'],
        ];

        foreach ($data as $item) {
            DB::table('pengaturan_bengkel')->updateOrCreate(
                ['key' => $item['key']],
                [
                    'value' => $item['value'],
                    'updated_at' => now(),
                ]
            );
        }
    }
}
