<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sizes')->insert([
            ['nama_size' => 'S'],
            ['nama_size' => 'M'],
            ['nama_size' => 'L'],
            ['nama_size' => 'XL'],
            ['nama_size' => 'XXL'],
            ['nama_size' => 'ALL Size']
        ]);
    }
}
