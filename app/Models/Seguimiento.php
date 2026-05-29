<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Seguimiento
 *
 * Representa la tabla 'seguimientos' de la base de datos.
 * Bitacora de cambios de estado en los reportes.
 *
 * Cada vez que un administrador cambia el estado de un reporte,
 * se registra automaticamente en esta tabla quien, cuando y que cambio se hizo.
 *
 */
class Seguimiento extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'seguimientos';

    /**
     * Atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reporte_id',
        'user_id',
        'estado_anterior',
        'estado_nuevo',
        'observacion',
    ];

    // ============================================================
    // RELACIONES
    // ============================================================

    /**
     * Relacion N:1 - Un seguimiento pertenece a un reporte.
     *
     * @return BelongsTo
     */
    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class, 'reporte_id');
    }

    /**
     * Relacion N:1 - Un seguimiento es realizado por un administrador (usuario).
     *
     * @return BelongsTo
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
