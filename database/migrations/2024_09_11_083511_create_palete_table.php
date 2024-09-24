<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('palete', function (Blueprint $table) {
            $table->id();
            $table->string('localizacao', 45)->nullable();
            $table->string('observacao', 255)->nullable();
            $table->dateTime('data_entrada')->nullable();
            $table->dateTime('data_saida')->nullable();
            $table->foreignId('tipo_palete_id')->constrained('tipo_palete', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('linha_documento_id')->constrained('linha_documento', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('artigo_id')->nullable()->constrained('artigo', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('armazem_id')->nullable()->constrained('armazem', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('cliente_id')->constrained('cliente', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('user', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('palete');
    }
};
