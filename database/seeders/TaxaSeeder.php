<?php

namespace Database\Seeders;

use App\Models\Armazem;
use App\Models\Taxa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxa = [
            [
                'nome' => 'Taxa de Entrega',
                'valor' => 2,
                'user_id' => 1
            ],
            [
                'nome' => 'Taxa de Retirada',
                'valor' => 2,
                'user_id' => 1
            ],
        ];

        foreach ($taxa as $taxaData) {
            Taxa::create($taxaData);
        }
    }
}
