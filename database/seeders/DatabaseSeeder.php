<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            mapelSeeder::class,
            kelasSeeder::class,
            UserSeeder::class,
            ujianSeeder::class,
            jawabansiswaSeeder::class,
            nilaiSeeder::class,
        ]);
    }
}
