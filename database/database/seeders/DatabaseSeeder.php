<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Developer: Hesa Khansa Arka
// NIM: L0124158

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SizeSeeder::class,       
            CategorySeeder::class,   
            ProductSeeder::class,    
        ]);
    }
}