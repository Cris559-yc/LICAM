<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Creación de la tabla 'categorias'
 *
 * Almacena los tipos de problemas que pueden reportar los ciudadanos
 * (basura, alumbrado, calles, postes, drenajes, etc.).
 *
 * El administrador puede agregar nuevas categorías desde el panel sin modificar el código.
 *
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla 'categorias' en la base de datos.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            // Llave primaria autoincremental
            $table->id();

            // Nombre unico de la categoria (ej: 'Calles y Baches', 'Alumbrado Publico')
            $table->string('nombre', 80)->unique()->comment('Nombre de la categoria');

            // Descripcion opcional del tipo de problema
            $table->string('descripcion', 255)->nullable()->comment('Descripcion del tipo de problema');

            // Icono representativo (ej: nombre de un emoji o clase de FontAwesome)
            $table->string('icono', 50)->nullable()->comment('Icono representativo de la categoria');

            // Color identificativo en formato hexadecimal (ej: '#FF5733')
            $table->string('color', 7)->nullable()->comment('Color hexadecimal para identificar la categoria en el mapa');

            // Indica si la categoria esta disponible para nuevos reportes
            $table->boolean('activo')->default(true)->comment('Si esta en FALSE, la categoria no se muestra al ciudadano');

            // Marcas de tiempo automaticas
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración: elimina la tabla 'categorias'.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
