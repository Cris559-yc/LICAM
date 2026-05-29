<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder principal: DatabaseSeeder
 *
 * Punto de entrada para ejecutar todos los seeders del sistema.
 * Define el ORDEN en que se ejecutan los seeders, lo cual es importante
 * porque algunos dependen de datos creados por otros (ej: UserSeeder
 * necesita que RolSeeder se ejecute primero).
 *
 * Para ejecutar todos los seeders: php artisan db:seed
 *
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders en el orden definido.
     *
     * @return void
     */
    public function run(): void
    {
        // El orden es importante por las dependencias entre tablas
        $this->call([
            RolSeeder::class,        // 1. Primero los roles
            CategoriaSeeder::class,  // 2. Luego las categorias (independiente)
            UserSeeder::class,       // 3. Por ultimo los usuarios (depende de roles)
        ]);
    }
}
