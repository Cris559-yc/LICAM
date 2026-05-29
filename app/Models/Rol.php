<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Rol
 *
 * Representa la tabla 'roles' de la base de datos.
 * Almacena los tipos de usuarios del sistema (ciudadano, administrador).
 *
 */
class Rol extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     * Se especifica manualmente porque Laravel pluralizaria 'Rol' como 'rols'.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Atributos que pueden ser asignados masivamente.
     * Protege contra ataques de Mass Assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // ============================================================
    // RELACIONES
    // ============================================================

    /**
     * Relacion 1:N - Un rol puede tener muchos usuarios asignados.
     *
     * @return HasMany
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'rol_id');
    }
}
