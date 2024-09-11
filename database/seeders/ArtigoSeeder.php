<?php

namespace Database\Seeders;

use App\Models\Artigo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtigoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artigo = [
            [
                'nome' => 'Pratos plásticos',
                'referencia' => 'PSP',
                'cliente_id' => 1,
                'user_id' => 1
            ],
            [
                'nome' => 'Pratos vidro',
                'referencia' => 'PSV',
                'cliente_id' => 1,
                'user_id' => 1
            ],
            [
                'nome' => 'Peças motor',
                'referencia' => 'PSM',
                'cliente_id' => 2,
                'user_id' => 1
            ],
        ];

        foreach ($artigo as $artigoData) {
            Artigo::create($artigoData);
        }
    }
}
