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
        Schema::create('registros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_vot');
            $table->unsignedBigInteger('id_nom');
            $table->unsignedBigInteger('id_grup');
            $table->unsignedBigInteger('id_conc');
            $table->unsignedInteger('ronda')->default(0);
            $table->timestamps();

            $table->foreign('id_vot')->references('id')->on('empleados');
            $table->foreign('id_nom')->references('id')->on('empleados');
            $table->foreign('id_grup')->references('id')->on('grupos');
            $table->foreign('id_conc')->references('id')->on('concursos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
