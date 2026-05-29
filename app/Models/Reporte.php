<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Reporte
 *
 * Representa la tabla 'reportes' de la base de datos.
 * Modelo central del sistema LICAM.
 *
 * Cada reporte representa un problema reportado por un ciudadano,
 * con su ubicacion geografica, descripcion, evidencia fotografica
 * y seguimiento del estado de resolucion.
 *
 */
class Reporte extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'reportes';

    /**
     * Atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'categoria_id',
        'titulo',
        'descripcion',
        'latitud',
        'longitud',
        'direccion',
        'estado',
        'prioridad',
        'fecha_reporte',
        'fecha_resolucion',
    ];

    /**
     * Conversion automatica de tipos para los atributos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'fecha_reporte' => 'datetime',
        'fecha_resolucion' => 'datetime',
    ];

    // ============================================================
    // CONSTANTES PARA ESTADOS Y PRIORIDADES
    // ============================================================

    /**
     * Valores permitidos para el estado del reporte.
     */
    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_EN_PROCESO = 'en_proceso';
    public const ESTADO_RESUELTO = 'resuelto';
    public const ESTADO_RECHAZADO = 'rechazado';

    /**
     * Valores permitidos para la prioridad del reporte.
     */
    public const PRIORIDAD_BAJA = 'baja';
    public const PRIORIDAD_MEDIA = 'media';
    public const PRIORIDAD_ALTA = 'alta';
    public const PRIORIDAD_URGENTE = 'urgente';

    // ============================================================
    // RELACIONES
    // ============================================================

    /**
     * Relacion N:1 - Un reporte pertenece al ciudadano que lo creo.
     *
     * @return BelongsTo
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relacion N:1 - Un reporte pertenece a una categoria.
     *
     * @return BelongsTo
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Relacion 1:N - Un reporte puede tener varias imagenes de evidencia.
     *
     * @return HasMany
     */
    public function imagenes(): HasMany
    {
        return $this->hasMany(Imagen::class, 'reporte_id');
    }

    /**
     * Relacion 1:N - Un reporte tiene varios seguimientos en su historial.
     *
     * @return HasMany
     */
    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class, 'reporte_id');
    }

    /**
     * Relacion 1:N - Un reporte puede tener varios comentarios.
     *
     * @return HasMany
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'reporte_id');
    }

    // ============================================================
    // METODOS DE AYUDA (HELPERS)
    // ============================================================

    /**
     * Verifica si el reporte esta pendiente de atencion.
     *
     * @return bool
     */
    public function estaPendiente(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    /**
     * Verifica si el reporte ya fue resuelto.
     *
     * @return bool
     */
    public function estaResuelto(): bool
    {
        return $this->estado === self::ESTADO_RESUELTO;
    }
}
