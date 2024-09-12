<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoDocumento = [
            [
                'nome' => 'Pedido de Entrega',
                'user_id' => 1
            ],
            [
                'nome' => 'Receção de Entrega',
                'user_id' => 1
            ],
            [
                'nome' => 'Pedido de Retirada',
                'user_id' => 1
            ],
            [
                'nome' => 'Guia de Transporte',
                'user_id' => 1
            ],
            [
                'nome' => 'Faturação',
                'user_id' => 1
            ],
        ];

        foreach ($tipoDocumento as $tipoDocumentoData) {
            TipoDocumento::create($tipoDocumentoData);
        }
    }
}
