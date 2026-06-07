<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'T-Shirt', 'Shirt', 'Polo', 'Crewneck', 
            'Hoodie', 'Jacket', 'Jeans', 'Pants', 'Accessories'
        ];
        
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'nama_category' => $category
            ]);
        }
    }
}