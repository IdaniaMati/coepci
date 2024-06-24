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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_grup')->constrained('grupos');//grupos
            $table->string('curp', 18)->nullable();
            $table->string('nombre', 255)->nullable();
            $table->string('apellido_paterno', 255)->nullable();
            $table->string('apellido_materno', 255)->nullable();
            $table->string('cargo', 255)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
