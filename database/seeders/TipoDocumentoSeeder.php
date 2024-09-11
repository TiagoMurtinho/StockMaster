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
                'nome' => 'Pedido entrega',
                'user_id' => 1
            ],
            [
                'nome' => 'Receção entrega',
                'user_id' => 1
            ],
            [
                'nome' => 'Pedido retirada',
                'user_id' => 1
            ],
        ];

        foreach ($tipoDocumento as $tipoDocumentoData) {
            TipoDocumento::create($tipoDocumentoData);
        }
    }
}
