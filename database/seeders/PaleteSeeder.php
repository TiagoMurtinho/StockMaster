<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100000; $i++) {
            DB::table('palete')->insert([
                'localizacao' => $faker->bothify('?#'),
                'observacao' => $faker->text('255'),
                'data_entrada' => now(),
                'data_saida' => $faker->dateTimeBetween('now', '+30 days'),
                'tipo_palete_id' => $faker->numberBetween('1', '3'),
                'documento_id' => $faker->numberBetween('1', '2000'),
                'artigo_id' => $faker->numberBetween('1', '10000'),
                'armazem_id' => $faker->numberBetween('1', '3'),
                'cliente_id' => $faker->numberBetween('1', '1000'),
                'user_id' => $faker->numberBetween('1', '50'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
