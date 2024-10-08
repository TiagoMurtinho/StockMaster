<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentoTipoDoisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 2000; $i++) {
            DB::table('documento')->insert([
                'estado' => 'terminado',
                'numero' => $faker->numberBetween('1', '2000'),
                'data' => now(),
                'previsao' => $faker->dateTimeBetween('now', '+30 days'),
                'data_entrada' => now(),
                'observacao' => $faker->text('255'),
                'tipo_documento_id' => 2,
                'cliente_id' => $faker->numberBetween('1', '1000'),
                'taxa_id' => 1,
                'user_id' => $faker->numberBetween('1', '5'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
