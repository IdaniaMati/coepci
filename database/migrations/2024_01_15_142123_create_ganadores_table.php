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
        Schema::create('ganadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_conc')->constrained('concursos')->index('ganadores_id_conc_foreign');
            $table->foreignId('id_grup')->constrained('grupos')->index('ganadores_id_grup_foreign');
            $table->foreignId('id_emp')->constrained('empleados')->index('ganadores_id_emp_foreign');
            $table->foreignId('id_depen')->constrained('dependencias');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ganadores');
    }
};
