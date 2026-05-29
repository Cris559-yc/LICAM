<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Creación de la tabla 'roles'
 *
 * Esta tabla almacena los tipos de usuarios del sistema (ciudadano, administrador).
 * Permite escalar a más roles en el futuro sin modificar el código fuente.
 *
 * Es la primera tabla en crearse porque otras tablas dependen de ella mediante llaves foráneas.
 *
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla 'roles' en la base de datos.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            // Llave primaria autoincremental de tipo BIGINT UNSIGNED
            $table->id();

            // Nombre unico del rol (ej: 'ciudadano', 'administrador')
            $table->string('nombre', 50)->unique()->comment('Nombre identificador del rol');

            // Descripcion opcional con las funciones del rol
            $table->string('descripcion', 255)->nullable()->comment('Descripcion de las funciones del rol');

            // Marcas de tiempo automaticas (created_at y updated_at)
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración: elimina la tabla 'roles' de la base de datos.
     * Se ejecuta al usar el comando 'php artisan migrate:rollback'.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
