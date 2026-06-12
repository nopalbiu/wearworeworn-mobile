<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'AdminWWW',
                'email' => 'adminwww@gmail.com',
                'password' => Hash::make('admin123'),
                'id_role' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}