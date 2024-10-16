<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(TipoPaleteSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(ArmazemSeeder::class);
        $this->call(TaxaSeeder::class);
    }
}
