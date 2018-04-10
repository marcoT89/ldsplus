<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('wards')->insert([
            ['name' => 'Gama I', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gama II', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gama Centro', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Santa Maria I', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Santa Maria II', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
