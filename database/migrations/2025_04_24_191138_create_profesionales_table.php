<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profesionales', function (Blueprint $table) {
            $table->uuid('id_profesional')->primary();
            $table->uuid('id_usuario');
            $table->string('especialidad');
            $table->text('descripcion')->nullable();
            $table->string('experiencia')->nullable();
            $table->text('biografia')->nullable();
            $table->string('ubicacion')->nullable();
            $table->float('calificacion_promedio')->default(0);
            $table->integer('cantidad_pacientes')->default(0);
            $table->string('numero_cuenta')->nullable();
            $table->string('banco')->nullable();
            $table->string('ci')->nullable();
            $table->string('profesion')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesionales');
    }
};
