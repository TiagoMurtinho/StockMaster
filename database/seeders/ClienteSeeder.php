<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente = [
            [
                'nome' => 'Visabeira',
                'morada' => 'Rua da progamação nº29',
                'codigo_postal' => '2500-123',
                'nif' => 251244765,
                'user_id' => 1
            ],
            [
                'nome' => 'Bordallo Pinheiro',
                'morada' => 'Rua da progamação nº23',
                'codigo_postal' => '2500-124',
                'nif' => 231233789,
                'user_id' => 1
            ],
            [
                'nome' => 'Vista Alegre',
                'morada' => 'Rua da progamação nº12',
                'codigo_postal' => '2500-121',
                'nif' => 212233432,
                'user_id' => 1
            ],
        ];

        foreach ($cliente as $clienteData) {
            Cliente::create($clienteData);
        }
    }
}
