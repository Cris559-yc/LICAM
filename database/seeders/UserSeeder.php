<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder: UserSeeder
 *
 * Inserta usuarios iniciales para pruebas y administracion del sistema:
 * - Un administrador con permisos completos
 * - Un ciudadano de prueba para validar el flujo de reportes
 *
 * IMPORTANTE: Las contraseñas se almacenan encriptadas mediante bcrypt.
 * Estos usuarios son SOLO para desarrollo y pruebas.
 *
 */
class UserSeeder extends Seeder
{
    /**
     * Ejecuta el seeder: inserta los usuarios iniciales.
     *
     * @return void
     */
    public function run(): void
    {
        // Obtener los IDs de los roles previamente creados por RolSeeder
        $rolAdministrador = Rol::where('nombre', 'administrador')->first();
        $rolCiudadano = Rol::where('nombre', 'ciudadano')->first();

        // Usuario Administrador de prueba
        User::create([
            'rol_id' => $rolAdministrador->id,
            'name' => 'Ana',
            'apellido' => 'Ramirez',
            'dui' => '01234567-8',
            'email' => 'admin@licam.test',
            'password' => Hash::make('admin12345'),
            'telefono' => '7777-8888',
            'direccion' => 'Caserio el mogote, San Jorge',
            'activo' => true,
        ]);

        // Usuario Ciudadano de prueba
        User::create([
            'rol_id' => $rolCiudadano->id,
            'name' => 'Carlos',
            'apellido' => 'Mendoza',
            'dui' => '12345678-9',
            'email' => 'carlos@licam.test',
            'password' => Hash::make('carlos12345'),
            'telefono' => '7888-9900',
            'direccion' => 'Barrio concepcion, San Jorge',
            'activo' => true,
        ]);
    }
}
