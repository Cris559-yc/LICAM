<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Creación de la tabla 'comentarios'
 *
 * Permite la comunicacion entre ciudadanos y administradores sobre un reporte.
 * Por ejemplo: pedir mas informacion sobre el problema, notificar avances,
 * o agradecer la resolucion.
 *
 * Tanto el ciudadano que creo el reporte como cualquier administrador
 * pueden escribir comentarios.
 *
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla 'comentarios'.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            // Llave primaria autoincremental
            $table->id();

            // Reporte al que pertenece el comentario (se borra en cascada)
            $table->foreignId('reporte_id')
                  ->constrained('reportes')
                  ->onDelete('cascade')
                  ->comment('Reporte al que pertenece el comentario');

            // Usuario que escribe el comentario (puede ser ciudadano o administrador)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Usuario que escribio el comentario');

            // Contenido del comentario
            $table->text('contenido')->comment('Texto del comentario');

            // Marcas de tiempo automaticas
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración: elimina la tabla 'comentarios'.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
