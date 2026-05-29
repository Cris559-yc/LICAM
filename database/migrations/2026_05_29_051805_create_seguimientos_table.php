<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Creación de la tabla 'seguimientos'
 *
 * Bitacora de cambios de estado en los reportes.
 * Cada vez que un administrador modifica el estado de un reporte
 * (de pendiente a en_proceso, de en_proceso a resuelto, etc.),
 * se registra automaticamente en esta tabla.
 *
 * Vital para la transparencia ante la ciudadania y para auditoria interna.
 *
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla 'seguimientos'.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('seguimientos', function (Blueprint $table) {
            // Llave primaria autoincremental
            $table->id();

            // Reporte al que pertenece el seguimiento (se borra en cascada)
            $table->foreignId('reporte_id')
                  ->constrained('reportes')
                  ->onDelete('cascade')
                  ->comment('Reporte al que pertenece este seguimiento');

            // Administrador que realizo el cambio
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('restrict')
                  ->comment('Administrador que realizo el cambio de estado');

            // Estado previo del reporte (NULL si es el primer cambio)
            $table->enum('estado_anterior', ['pendiente', 'en_proceso', 'resuelto', 'rechazado'])
                  ->nullable()
                  ->comment('Estado del reporte antes del cambio');

            // Nuevo estado al que cambio el reporte
            $table->enum('estado_nuevo', ['pendiente', 'en_proceso', 'resuelto', 'rechazado'])
                  ->comment('Estado del reporte despues del cambio');

            // Observacion o comentario del administrador sobre el cambio
            $table->text('observacion')
                  ->nullable()
                  ->comment('Observacion del administrador sobre el cambio');

            // Marcas de tiempo automaticas
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración: elimina la tabla 'seguimientos'.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
