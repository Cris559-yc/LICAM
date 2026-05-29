<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

/**
 * Seeder: CategoriaSeeder
 *
 * Inserta las categorias de problemas que pueden reportar los ciudadanos.
 * Cada categoria incluye un icono representativo y un color identificativo
 * que se usaran en el mapa y en la interfaz de usuario.
 *
 * El administrador puede agregar nuevas categorias posteriormente desde
 * el panel administrativo.
 *
 */
class CategoriaSeeder extends Seeder
{
    /**
     * Ejecuta el seeder: inserta las categorias base del sistema.
     *
     * @return void
     */
    public function run(): void
    {
        // Arreglo con las categorias iniciales del sistema
        $categorias = [
            [
                'nombre' => 'Calles y Baches',
                'descripcion' => 'Pavimento dañado, hoyos, agrietamientos en calles y avenidas.',
                'icono' => '🚧',
                'color' => '#EF4444',
                'activo' => true,
            ],
            [
                'nombre' => 'Alumbrado Publico',
                'descripcion' => 'Postes caidos, luminarias dañadas o apagadas.',
                'icono' => '💡',
                'color' => '#F59E0B',
                'activo' => true,
            ],
            [
                'nombre' => 'Basura y Limpieza',
                'descripcion' => 'Zonas llenas de desechos, vertederos clandestinos.',
                'icono' => '🗑️',
                'color' => '#10B981',
                'activo' => true,
            ],
            [
                'nombre' => 'Agua y Drenaje',
                'descripcion' => 'Fugas de agua, tuberias rotas, alcantarillado obstruido.',
                'icono' => '💧',
                'color' => '#3B82F6',
                'activo' => true,
            ],
            [
                'nombre' => 'Parques y Areas Verdes',
                'descripcion' => 'Espacios publicos deteriorados, jardines abandonados.',
                'icono' => '🌳',
                'color' => '#8B5CF6',
                'activo' => true,
            ],
            [
                'nombre' => 'Señalizacion Vial',
                'descripcion' => 'Semaforos, señales de transito dañadas o ausentes.',
                'icono' => '🚦',
                'color' => '#EC4899',
                'activo' => true,
            ],
            [
                'nombre' => 'Obras Publicas',
                'descripcion' => 'Infraestructura municipal en mal estado.',
                'icono' => '🏚️',
                'color' => '#06B6D4',
                'activo' => true,
            ],
            [
                'nombre' => 'Otros',
                'descripcion' => 'Otras incidencias urbanas que no encajan en categorias previas.',
                'icono' => '📋',
                'color' => '#64748B',
                'activo' => true,
            ],
        ];

        // Insertar cada categoria en la base de datos
        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
