<?php

namespace Database\Seeders;

use App\Models\Artigo;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtigoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10000; $i++) {
            DB::table('artigo')->insert([
                'nome' => $faker->name,
                'referencia' => strtoupper($faker->lexify('???')),
                'cliente_id' => $faker->numberBetween('1', '1000'),
                'user_id' => $faker->numberBetween('1', '5'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
