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
        Schema::create('concursos', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('descripcion', 255)->nullable();
            $table->date('fechaIni1ronda')->nullable();
            $table->date('fechaIni2ronda')->nullable();
            $table->date('fechaFin')->nullable();
            $table->text('comentario')->nullable();
            $table->integer('id_depen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concursos');
    }
};
