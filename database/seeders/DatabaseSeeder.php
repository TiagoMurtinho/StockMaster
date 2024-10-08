<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Palete;
use App\Models\TipoPalete;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call(ClienteSeeder::class);
        $this->call(ArtigoSeeder::class);
        $this->call(ArmazemSeeder::class);
        $this->call(TaxaSeeder::class);
        $this->call(DocumentoTipoUmSeeder::class);
        $this->call(LinhaDocumentoTipoUmSeeder::class);
        $this->call(PaleteSeeder::class);
        $this->call(DocumentoTipoDoisSeeder::class);
        $this->call(LinhaDocumentoTipoDoisSeeder::class);
        $this->call(DocumentoTipoTresSeeder::class);
        $this->call(LinhaDocumentoTipoTresSeeder::class);
        $this->call(DocumentoTipoQuatroSeeder::class);
        $this->call(LinhaDocumentoTipoQuaatroSeeder::class);
        $this->call(DocumentoTipoCincoSeeder::class);
    }
}
