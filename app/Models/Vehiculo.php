<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Vehiculo extends Model
{
    use HasSlug;

    protected $fillable = [
        'agencia_id', 'user_id', 'tipo', 'marca', 'modelo', 'anio', 'version',
        'precio', 'precio_negociable', 'kilometraje', 'transmision', 'combustible',
        'color', 'puertas', 'cilindros', 'motor', 'descripcion', 'slug', 'status',
        'destacado', 'vistas', 'ciudad', 'estado', 'vin', 'placas',
    ];

    protected $casts = [
        'precio'            => 'decimal:2',
        'precio_negociable' => 'boolean',
        'destacado'         => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($model) => "{$model->marca} {$model->modelo} {$model->version} {$model->anio}")
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Relaciones ──────────────────────────────────────────────

    public function agencia(): BelongsTo
    {
        return $this->belongsTo(Agencia::class);
    }

    public function capturista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(VehiculoFoto::class)->orderBy('orden');
    }

    public function fotoPrincipal(): HasOne
    {
        return $this->hasOne(VehiculoFoto::class)->where('es_principal', true)->orderBy('orden');
    }

    public function certificaciones(): HasMany
    {
        return $this->hasMany(Certificacion::class);
    }

    public function certificacion(): HasOne
    {
        return $this->hasOne(Certificacion::class)->where('resultado', 'aprobado')->latestOfMany();
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class);
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function estaCertificado(): bool
    {
        return $this->certificaciones()->where('resultado', 'aprobado')->exists();
    }

    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format($this->precio, 0, '.', ',');
    }

    public function getKilometrajeFormateadoAttribute(): string
    {
        return number_format($this->kilometraje, 0, '.', ',') . ' km';
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeDisponibles($query)
    {
        return $query->where('status', 'disponible');
    }

    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    public function scopeCertificados($query)
    {
        return $query->whereHas('certificaciones', fn ($q) => $q->where('resultado', 'aprobado'));
    }
}
