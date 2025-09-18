<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Llamar a los seeders que creaste
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            ProfileSeeder::class,
            CategorySeeder::class,
            PublicationSeeder::class,
            MessageSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
