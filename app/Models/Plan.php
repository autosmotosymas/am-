<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $table = 'planes';

    protected $fillable = [
        'slug', 'nombre', 'duracion', 'meses', 'precio',
        'max_vehiculos', 'max_fotos_por_vehiculo', 'incluye_certificacion',
        'vehiculos_destacados', 'videos', 'badge_premium',
        'features', 'activo', 'stripe_price_id',
    ];

    protected $casts = [
        'incluye_certificacion' => 'boolean',
        'badge_premium'         => 'boolean',
        'activo'                => 'boolean',
        'features'              => 'array',
        'precio'                => 'decimal:2',
    ];

    public function tieneStripe(): bool
    {
        return ! empty($this->stripe_price_id);
    }

    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format($this->precio, 0, '.', ',');
    }

    public function getDuracionLabelAttribute(): string
    {
        return match ($this->duracion) {
            'trimestral' => '3 meses',
            'semestral'  => '6 meses',
            'anual'      => '12 meses',
            default      => $this->duracion,
        };
    }

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class);
    }
}
