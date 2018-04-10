<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Marco Túlio',
            'email' => 'marcotulio.avila@gmail.com',
            'password' => bcrypt('123456'),
            'birthday' => Carbon::createFromDate(1989, 6, 28),
            'gender' => 'male',
            'ward_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
