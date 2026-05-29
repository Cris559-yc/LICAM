<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Comentario
 *
 * Representa la tabla 'comentarios' de la base de datos.
 * Permite la comunicacion entre ciudadanos y administradores sobre un reporte.
 *
 */
class Comentario extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'comentarios';

    /**
     * Atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reporte_id',
        'user_id',
        'contenido',
    ];

    // ============================================================
    // RELACIONES
    // ============================================================

    /**
     * Relacion N:1 - Un comentario pertenece a un reporte.
     *
     * @return BelongsTo
     */
    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class, 'reporte_id');
    }

    /**
     * Relacion N:1 - Un comentario es escrito por un usuario.
     *
     * @return BelongsTo
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
