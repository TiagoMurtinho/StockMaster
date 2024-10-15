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
        Schema::create('documento', function (Blueprint $table) {
            $table->id();
            $table->string('estado')->default('pendente');
            $table->integer('numero');
            $table->string('matricula', 45)->nullable();
            $table->string('morada', 255)->nullable();
            $table->date('data');
            $table->date('previsao')->nullable();
            $table->dateTime('data_entrada')->nullable();
            $table->dateTime('data_saida')->nullable();
            $table->dateTime('previsao_descarga')->nullable();
            $table->float('extra')->nullable();
            $table->float('total')->nullable();
            $table->string('observacao', 255)->nullable();
            $table->foreignId('tipo_documento_id')->constrained('tipo_documento', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('cliente_id')->constrained('cliente','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('taxa_id')->nullable()->constrained('taxa', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento');
    }
};
