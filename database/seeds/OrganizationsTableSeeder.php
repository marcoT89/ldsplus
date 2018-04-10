<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationsTableSeeder extends Seeder
{
    use WithDefaults;

    public function run()
    {
        DB::table('organizations')->insert([
            $this->withTimestamps(['name' => 'Bispado']), // 1
            $this->withTimestamps(['name' => 'Quórum de Élderes']), // 2
            $this->withTimestamps(['name' => 'Sociedade de Socorro']), // 3
            $this->withTimestamps(['name' => 'Rapazes']), // 4
            $this->withTimestamps(['name' => 'Moças']), // 5
            $this->withTimestamps(['name' => 'Escola Dominical']), // 6
            $this->withTimestamps(['name' => 'Primária']), // 7
            $this->withTimestamps(['name' => 'Jovens Adultos Solteiros']), // 8
            $this->withTimestamps(['name' => 'Adultos Solteiros']), // 9
            $this->withTimestamps(['name' => 'Música']), // 10
            $this->withTimestamps(['name' => 'Manutenção e Registros']), // 11
            $this->withTimestamps(['name' => 'História da Família']), // 12
            $this->withTimestamps(['name' => 'Obra Missionária']), // 13
            $this->withTimestamps(['name' => 'Seminário']), // 14
        ]);
    }
}
