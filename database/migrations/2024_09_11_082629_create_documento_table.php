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
            $table->integer('numero');
            $table->dateTime('data');
            $table->string('matricula', 45)->nullable();
            $table->string('morada', 255)->nullable();
            $table->time('hora_carga')->nullable();
            $table->dateTime('descarga')->nullable();
            $table->float('total')->nullable();
            $table->foreignId('tipo_documento_id')->constrained('tipo_documento', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('cliente_id')->constrained('cliente','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('user', 'id')->cascadeOnDelete()->cascadeOnUpdate();
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
