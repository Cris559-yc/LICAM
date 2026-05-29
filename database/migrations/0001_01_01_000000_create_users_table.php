<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: Modificación de la tabla 'users'
 *
 * Agrega los campos adicionales necesarios para el sistema LICAM:
 * - Relacion con la tabla 'roles' (ciudadano o administrador)
 * - Datos personales del ciudadano (apellido, DUI, telefono, direccion)
 * - Estado del usuario (activo/inactivo)
 *
 * Se modifica la tabla existente 'users' que Laravel crea por defecto,
 * aprovechando su sistema de autenticacion integrado.
 *
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración: agrega columnas nuevas a la tabla 'users'.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Llave foranea hacia la tabla 'roles'.
            // Se coloca despues de 'id' para mejor organizacion visual.
            $table->foreignId('rol_id')
                  ->after('id')
                  ->constrained('roles')
                  ->onDelete('restrict')
                  ->comment('Rol asignado al usuario: ciudadano o administrador');

            // Apellido del usuario (Laravel ya trae 'name' para el nombre)
            $table->string('apellido', 100)
                  ->after('name')
                  ->comment('Apellido(s) del usuario');

            // Documento Unico de Identidad (formato salvadoreño: 00000000-0)
            $table->string('dui', 10)
                  ->unique()
                  ->nullable()
                  ->after('apellido')
                  ->comment('Documento Unico de Identidad en formato 00000000-0');

            // Numero de telefono del usuario
            $table->string('telefono', 15)
                  ->nullable()
                  ->after('dui')
                  ->comment('Numero de telefono de contacto');

            // Direccion de residencia del usuario
            $table->string('direccion', 255)
                  ->nullable()
                  ->after('telefono')
                  ->comment('Direccion de residencia del usuario');

            // Estado del usuario: TRUE = activo, FALSE = deshabilitado
            $table->boolean('activo')
                  ->default(true)
                  ->after('direccion')
                  ->comment('Indica si el usuario puede iniciar sesion');
        });
    }

    /**
     * Revierte la migración: elimina las columnas agregadas a 'users'.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Primero eliminar la restriccion de llave foranea
            $table->dropForeign(['rol_id']);

            // Luego eliminar las columnas en orden inverso
            $table->dropColumn([
                'rol_id',
                'apellido',
                'dui',
                'telefono',
                'direccion',
                'activo'
            ]);
        });
    }
};
