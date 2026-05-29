<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Imagen
 *
 * Representa la tabla 'imagenes' de la base de datos.
 * Almacena las imagenes de evidencia adjuntadas a cada reporte.
 *
 * Las imagenes fisicas se guardan en storage/app/public/reportes/
 * y este modelo solo mantiene la referencia (URL) a cada archivo.
 *
 */
class Imagen extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'imagenes';

    /**
     * Atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reporte_id',
        'url',
        'nombre_archivo',
        'tamano',
        'tipo_mime',
    ];

    // ============================================================
    // RELACIONES
    // ============================================================

    /**
     * Relacion N:1 - Una imagen pertenece a un reporte.
     *
     * @return BelongsTo
     */
    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class, 'reporte_id');
    }
}
