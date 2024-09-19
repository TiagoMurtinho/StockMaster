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
            $table->string('observacao', 255)->nullable();
            $table->float('valor')->nullable();
            $table->string('morada', 255)->nullable();
            $table->date('previsao')->nullable();
            $table->dateTime('data_entrada')->nullable();
            $table->dateTime('data_saida')->nullable();
            $table->float('extra')->nullable();
            $table->foreignId('documento_id')->constrained('documento', 'id')->cascadeOnDelete()->cascadeOnUpdate();
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
