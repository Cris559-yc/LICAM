<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Creación de la tabla 'imagenes'
 *
 * Almacena las imagenes de evidencia que el ciudadano adjunta a cada reporte.
 * Se usa una tabla separada porque un reporte puede tener varias fotos del problema.
 *
 * Las imagenes fisicas se guardan en storage/app/public/reportes/
 * y aqui se almacena solo la referencia (URL) a cada archivo.
 *
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla 'imagenes'.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('imagenes', function (Blueprint $table) {
            // Llave primaria autoincremental
            $table->id();

            // Reporte al que pertenece la imagen.
            // Si se borra el reporte, se borran sus imagenes automaticamente (CASCADE).
            $table->foreignId('reporte_id')
                  ->constrained('reportes')
                  ->onDelete('cascade')
                  ->comment('Reporte al que pertenece la imagen');

            // URL o ruta de almacenamiento de la imagen
            $table->string('url', 255)->comment('Ruta de almacenamiento de la imagen');

            // Nombre original del archivo cuando se subio
            $table->string('nombre_archivo', 150)->comment('Nombre original del archivo');

            // Tamaño del archivo en bytes
            $table->unsignedInteger('tamano')
                  ->nullable()
                  ->comment('Tamaño del archivo en bytes');

            // Tipo MIME del archivo (image/jpeg, image/png, etc.)
            $table->string('tipo_mime', 50)
                  ->nullable()
                  ->comment('Tipo MIME del archivo');

            // Marcas de tiempo automaticas
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración: elimina la tabla 'imagenes'.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
