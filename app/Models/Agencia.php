<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Agencia extends Model
{
    use HasSlug;

    protected $fillable = [
        'nombre', 'slug', 'email', 'telefono', 'whatsapp', 'direccion',
        'ciudad', 'estado', 'cp', 'logo', 'banner', 'descripcion',
        'calificacion', 'total_resenas', 'activo', 'verificada', 'vendedor_id',
    ];

    protected $casts = [
        'activo'     => 'boolean',
        'verificada' => 'boolean',
        'calificacion' => 'decimal:2',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nombre')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Relaciones ──────────────────────────────────────────────

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'agencia_user');
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class);
    }

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class);
    }

    public function suscripcionActiva(): HasOne
    {
        return $this->hasOne(Suscripcion::class)
            ->where('status', 'activa')
            ->where('fecha_vencimiento', '>=', now())
            ->latestOfMany();
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function tieneSuscripcionActiva(): bool
    {
        return $this->suscripcionActiva()->exists();
    }

    public function vehiculosActivosCount(): int
    {
        return $this->vehiculos()->where('status', 'disponible')->count();
    }
}
