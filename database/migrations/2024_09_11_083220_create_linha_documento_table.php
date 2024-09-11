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
        Schema::create('linha_documento', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade');
            $table->string('descricao', 255);
            $table->float('valor');
            $table->string('morada', 255)->nullable();
            $table->dateTime('data_entrega')->nullable();
            $table->dateTime('data_recolha')->nullable();
            $table->float('extra')->nullable();
            $table->foreignId('documento_id')->constrained('documento', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('tipo_palete_id')->constrained('tipo_palete', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('artigo_id')->constrained('artigo', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('user', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linha_documento');
    }
};