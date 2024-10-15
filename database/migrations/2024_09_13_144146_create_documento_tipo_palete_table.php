<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('documento_tipo_palete', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained('documento', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('tipo_palete_id')->constrained('tipo_palete', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('artigo_id')->constrained('artigo', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('armazem_id')->nullable()->constrained('armazem', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('localizacao', 45)->nullable();
            $table->integer('quantidade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_tipo_palete');
    }
};
