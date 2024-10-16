<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinhaDocumentoTipoTresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 6000; $i++) {
            DB::table('documento_tipo_palete')->insert([
                'documento_id' => $faker->numberBetween('1', '2000'),
                'tipo_palete_id' => $faker->numberBetween('1', '3'),
                'artigo_id' => $faker->numberBetween('1', '10000'),
                'quantidade' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
