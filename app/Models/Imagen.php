<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Modelo Imagen
 *
 * Representa la tabla 'imagenes' de la base de datos.
 * Almacena las imagenes de evidencia adjuntadas a cada reporte.
 *
 * Las imagenes fisicas se guardan en storage/app/public/reportes/
 * y este modelo mantiene la referencia (ruta) a cada archivo,
 * exponiendo ademas la URL publica completa para el frontend.
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

    /**
     * Atributos calculados que se agregan automaticamente al serializar.
     * 'url_completa' genera la URL publica accesible desde el navegador.
     *
     * @var array<int, string>
     */
    protected $appends = ['url_completa'];

    /**
     * Accesor: genera la URL publica completa de la imagen.
     * Convierte la ruta relativa (ej: 'reportes/foto.jpg') en una
     * URL accesible (ej: 'http://localhost:8000/storage/reportes/foto.jpg').
     *
     * @return string
     */
    public function getUrlCompletaAttribute(): string
    {
        return Storage::url($this->url);
    }


    // RELACIONES

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
