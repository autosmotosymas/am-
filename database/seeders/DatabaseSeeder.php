<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            PlanesSeeder::class,
            // UsersSeeder::class,      // después
            // AgenciasSeeder::class,   // después
            // VehiculosSeeder::class,  // después
        ]);
    }
}
