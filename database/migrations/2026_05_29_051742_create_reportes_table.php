<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Creación de la tabla 'reportes'
 *
 * Tabla principal del sistema LICAM. Almacena los reportes ciudadanos
 * de problemas urbanos (calles, alumbrado, basura, etc.).
 *
 * Cada reporte incluye:
 * - Relacion con el ciudadano que lo creo (users)
 * - Categoria asignada por el administrador (categorias)
 * - Ubicacion geografica (latitud/longitud)
 * - Estado y prioridad del problema
 *
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla 'reportes'.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            // Llave primaria autoincremental
            $table->id();

            // Ciudadano que creo el reporte
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Ciudadano que creo el reporte');

            // Categoria del problema (NULL hasta que el administrador la asigne)
            $table->foreignId('categoria_id')
                  ->nullable()
                  ->constrained('categorias')
                  ->onDelete('set null')
                  ->comment('Categoria asignada por el administrador');

            // Titulo breve del reporte
            $table->string('titulo', 150)->comment('Titulo descriptivo del reporte');

            // Descripcion detallada del problema
            $table->text('descripcion')->comment('Descripcion completa del problema');

            // Coordenadas geograficas con precision de 8 decimales
            $table->decimal('latitud', 10, 8)->comment('Coordenada de latitud (-90 a 90)');
            $table->decimal('longitud', 11, 8)->comment('Coordenada de longitud (-180 a 180)');

            // Direccion textual de la ubicacion (opcional)
            $table->string('direccion', 255)
                  ->nullable()
                  ->comment('Direccion textual del incidente');

            // Estado del reporte (flujo: pendiente -> en_proceso -> resuelto)
            $table->enum('estado', ['pendiente', 'en_proceso', 'resuelto', 'rechazado'])
                  ->default('pendiente')
                  ->comment('Estado actual del reporte');

            // Prioridad asignada por el administrador
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])
                  ->default('media')
                  ->comment('Prioridad de atencion del reporte');

            // Fecha en que el ciudadano envio el reporte
            $table->timestamp('fecha_reporte')
                  ->useCurrent()
                  ->comment('Fecha y hora de creacion del reporte');

            // Fecha en que el administrador marco el reporte como resuelto
            $table->timestamp('fecha_resolucion')
                  ->nullable()
                  ->comment('Fecha en que el reporte fue resuelto');

            // Marcas de tiempo automaticas de Laravel
            $table->timestamps();

            // Indices para mejorar el rendimiento en consultas frecuentes
            $table->index('estado');
            $table->index('prioridad');
            $table->index(['latitud', 'longitud']);
        });
    }

    /**
     * Revierte la migración: elimina la tabla 'reportes'.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
