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
        Schema::create('linha_documento_tipo_palete', function (Blueprint $table) {
            $table->id();
            $table->foreignId('linha_documento_id')->constrained('linha_documento', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('tipo_palete_id')->constrained('tipo_palete', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('artigo_id')->constrained('artigo', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('quantidade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linha_documento_tipo_palete');
    }
};
