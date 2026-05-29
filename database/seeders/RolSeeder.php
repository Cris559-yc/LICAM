<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

/**
 * Seeder: RolSeeder
 *
 * Inserta los roles base del sistema LICAM:
 * - ciudadano: usuario regular que reporta problemas
 * - administrador: usuario de la alcaldia que gestiona los reportes
 *
 * Estos roles son esenciales para el funcionamiento del sistema
 * y deben existir antes de crear cualquier usuario.
 *
 */
class RolSeeder extends Seeder
{
    /**
     * Ejecuta el seeder: inserta los roles base.
     *
     * @return void
     */
    public function run(): void
    {
        // Rol de Ciudadano (usuario regular)
        Rol::create([
            'nombre' => 'ciudadano',
            'descripcion' => 'Usuario que puede crear y dar seguimiento a sus reportes ciudadanos.',
        ]);

        // Rol de Administrador (personal de la alcaldia)
        Rol::create([
            'nombre' => 'administrador',
            'descripcion' => 'Personal de la alcaldia que clasifica y gestiona los reportes.',
        ]);
    }
}
