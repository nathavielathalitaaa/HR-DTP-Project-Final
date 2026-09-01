<?php

namespace Database\Seeders;

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
        // panggil production seeder agar akun admin@sinergihotel.com selalu terbuat
        $this->call([
            ProductionSeeder::class,
        ]);
    }
}
