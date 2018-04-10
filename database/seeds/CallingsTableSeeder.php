<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CallingsTableSeeder extends Seeder
{
    use WithDefaults;

    public function run()
    {
        DB::table('callings')->insert([
            // Bispado
            $this->withTimestamps(['name' => 'Bispo', 'gender' => 'male', 'organization_id' => 1]),
            $this->withTimestamps(['name' => '1º Conselheiro no Bispado', 'gender' => 'male', 'organization_id' => 1]),
            $this->withTimestamps(['name' => '2º Conselheiro no Bispado', 'gender' => 'male', 'organization_id' => 1]),
            $this->withTimestamps(['name' => 'Secretário da Ala', 'gender' => 'male', 'organization_id' => 1]),
            // Quórum de Élderes
            $this->withTimestamps(['name' => 'Presidente do Quórum de Élderes', 'gender' => 'male', 'organization_id' => 2]),
            $this->withTimestamps(['name' => '1º Conselheiro no Quórum de Élderes', 'gender' => 'male', 'organization_id' => 2]),
            $this->withTimestamps(['name' => '2º Conselheiro no Quórum de Élderes', 'gender' => 'male', 'organization_id' => 2]),
            $this->withTimestamps(['name' => 'Secretário do Quórum de Élderes', 'gender' => 'male', 'organization_id' => 2]),
            // Sociedade de Socorro
            $this->withTimestamps(['name' => 'Presidente da Sociedade de Socorro', 'gender' => 'female', 'organization_id' => 3]),
            $this->withTimestamps(['name' => '1ª Conselheira na Sociedade de Socorro', 'gender' => 'female', 'organization_id' => 3]),
            $this->withTimestamps(['name' => '2ª Conselheira na Sociedade de Socorro', 'gender' => 'female', 'organization_id' => 3]),
            $this->withTimestamps(['name' => 'Secretária da Sociedade de Socorro', 'gender' => 'female', 'organization_id' => 3]),
            // Rapazes
            $this->withTimestamps(['name' => 'Presidente dos Rapazes', 'gender' => 'male', 'organization_id' => 4]),
            $this->withTimestamps(['name' => '1º Conselheiro nos Rapazes', 'gender' => 'male', 'organization_id' => 4]),
            $this->withTimestamps(['name' => '2º Conselheiro nos Rapazes', 'gender' => 'male', 'organization_id' => 4]),
            $this->withTimestamps(['name' => 'Secretário dos Rapazes', 'gender' => 'male', 'organization_id' => 4]),
            // Moças
            $this->withTimestamps(['name' => 'Presidente das Moças', 'gender' => 'female', 'organization_id' => 5]),
            $this->withTimestamps(['name' => '1ª Conselheira nas Moças', 'gender' => 'female', 'organization_id' => 5]),
            $this->withTimestamps(['name' => '2ª Conselheira nas Moças', 'gender' => 'female', 'organization_id' => 5]),
            $this->withTimestamps(['name' => 'Secretária das Moças', 'gender' => 'female', 'organization_id' => 5]),
            // Escola Dominical
            $this->withTimestamps(['name' => 'Presidente da Escola Dominical', 'gender' => 'male', 'organization_id' => 6]),
            $this->withTimestamps(['name' => '1º Conselheiro na Escola Dominical', 'gender' => 'male', 'organization_id' => 6]),
            $this->withTimestamps(['name' => '2º Conselheiro na Escola Dominical', 'gender' => 'male', 'organization_id' => 6]),
            // Primária
            $this->withTimestamps(['name' => 'Presidente da Primária', 'gender' => 'female', 'organization_id' => 7]),
            $this->withTimestamps(['name' => '1ª Conselheira na Primária', 'gender' => 'female', 'organization_id' => 7]),
            $this->withTimestamps(['name' => '2ª Conselheira na Primária', 'gender' => 'female', 'organization_id' => 7]),
            $this->withTimestamps(['name' => 'Secretária da Primária', 'gender' => 'female', 'organization_id' => 7]),
            // Jovens Adultos Solteiros
            $this->withTimestamps(['name' => 'Líder Masculino dos Jovens Adultos Solteiros', 'gender' => 'male', 'organization_id' => 8]),
            $this->withTimestamps(['name' => 'Líder Feminina dos Jovens Adultos Solteiros', 'gender' => 'female', 'organization_id' => 8]),
            // Obra Missionária
            $this->withTimestamps(['name' => 'Líder de Missão da Ala', 'gender' => 'male', 'organization_id' => 13]),
            // Seminário
            $this->withTimestamps(['name' => 'Professor(a) do Seminário', 'gender' => 'both', 'organization_id' => 14]),
        ]);
    }
}
