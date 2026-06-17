<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed User
        $this->call([
        UserSeeder::class,
        ]);

        // Seed kategori_servis if not present
        $this->call(KategoriServisSeeder::class);
    }
}
