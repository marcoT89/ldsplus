<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            WardsTableSeeder::class,
            UsersTableSeeder::class,
            OrganizationsTableSeeder::class,
            CallingsTableSeeder::class,
        ]);
    }
}
