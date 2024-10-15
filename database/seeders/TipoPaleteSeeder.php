<?php

namespace Database\Seeders;

use App\Models\TipoPalete;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoPaleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoPalete = [
            [
                'tipo' => 'Palete Baixa',
                'valor' => 1.50,
                'user_id' => 1,
            ],
            [
                'tipo' => 'Palete Alta',
                'valor' => 2,
                'user_id' => 1,
            ],
            [
                'tipo' => 'Palete Frigorifica',
                'valor' => 3.50,
                'user_id' => 1,
            ]
        ];

        foreach ($tipoPalete as $tipoPaleteData) {
            TipoPalete::create($tipoPaleteData);
        }
    }
}
