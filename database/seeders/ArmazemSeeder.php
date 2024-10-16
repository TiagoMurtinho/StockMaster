<?php

namespace Database\Seeders;

use App\Models\Armazem;
use Illuminate\Database\Seeder;

class ArmazemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $armazem = [
            [
                'nome' => 'Armazem 1',
                'capacidade' => 5000,
                'tipo_palete_id' => 1,
                'user_id' => 1
            ],
            [
                'nome' => 'Armazem 2',
                'capacidade' => 10000,
                'tipo_palete_id' => 2,
                'user_id' => 1
            ],
            [
                'nome' => 'Armazem 3',
                'capacidade' => 2500,
                'tipo_palete_id' => 3,
                'user_id' => 1
            ],
        ];

        foreach ($armazem as $armazemData) {
            Armazem::create($armazemData);
        }
    }
}
