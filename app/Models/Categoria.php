<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Categoria
 *
 * Representa la tabla 'categorias' de la base de datos.
 * Almacena los tipos de problemas que pueden ser reportados por los ciudadanos
 * (calles, alumbrado, basura, agua, etc.).
 *
 */
class Categoria extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'categorias';

    /**
     * Atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'activo',
    ];

    /**
     * Conversion automatica de tipos para los atributos.
     * Convierte 'activo' a booleano al leer/escribir.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activo' => 'boolean',
    ];

    // ============================================================
    // RELACIONES
    // ============================================================

    /**
     * Relacion 1:N - Una categoria agrupa muchos reportes.
     *
     * @return HasMany
     */
    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class, 'categoria_id');
    }
}
