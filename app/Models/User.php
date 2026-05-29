<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modelo User
 *
 * Representa la tabla 'users' de la base de datos.
 * Esta tabla almacena tanto a ciudadanos como administradores del sistema,
 * diferenciados por su rol_id.
 *
 * Extiende de Authenticatable para usar el sistema de autenticacion de Laravel.
 *
 */
class User extends Authenticatable
{
   // use HasApiTokens, HasFactory, Notifiable;
   use HasFactory, Notifiable;

    /**
     * Atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rol_id',
        'name',
        'apellido',
        'dui',
        'email',
        'password',
        'telefono',
        'direccion',
        'activo',
    ];

    /**
     * Atributos que deben ocultarse al serializar el modelo.
     * Por seguridad, la contraseña y el token nunca se envian al frontend.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversion automatica de tipos para los atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    // ============================================================
    // RELACIONES
    // ============================================================

    /**
     * Relacion N:1 - Un usuario pertenece a un rol.
     *
     * @return BelongsTo
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Relacion 1:N - Un usuario (ciudadano) puede crear muchos reportes.
     *
     * @return HasMany
     */
    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class, 'user_id');
    }

    /**
     * Relacion 1:N - Un usuario (administrador) puede realizar muchos seguimientos.
     *
     * @return HasMany
     */
    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class, 'user_id');
    }

    /**
     * Relacion 1:N - Un usuario puede escribir muchos comentarios.
     *
     * @return HasMany
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'user_id');
    }

    // ============================================================
    // METODOS DE AYUDA (HELPERS)
    // ============================================================

    /**
     * Verifica si el usuario es administrador.
     *
     * @return bool
     */
    public function esAdministrador(): bool
    {
        return $this->rol && $this->rol->nombre === 'administrador';
    }

    /**
     * Verifica si el usuario es ciudadano.
     *
     * @return bool
     */
    public function esCiudadano(): bool
    {
        return $this->rol && $this->rol->nombre === 'ciudadano';
    }
}
