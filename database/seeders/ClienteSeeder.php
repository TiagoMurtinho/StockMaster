<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 1000; $i++) {
            DB::table('cliente')->insert([
                'nome' => $faker->name,
                'morada' => $faker->address,
                'codigo_postal' => $faker->bothify('####-###'),
                'nif' => $faker->numberBetween('10000000', '20000000'),
                'user_id' => $faker->numberBetween('1', '5'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
