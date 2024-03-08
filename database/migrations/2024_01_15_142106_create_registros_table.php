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
            $table->id();
            $table->foreignId('id_vot')->constrained('empleados')->index('registros_id_vot_foreign'); 
            $table->foreignId('id_nom')->constrained('empleados')->index('registros_id_nom_foreign'); 
            $table->foreignId('id_grup')->constrained('grupos')->index('registros_id_grup_foreign'); 
            $table->foreignId('id_conc')->constrained('concursos')->index('registros_id_conc_foreign'); 
            $table->date('fecha')->nullable();
            $table->foreignId('id_depen')->constrained('dependencias');
            $table->timestamps();
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
