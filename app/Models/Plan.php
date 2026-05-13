<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $table = 'planes';

    protected $fillable = [
        'slug', 'nombre', 'precio_mensual', 'max_vehiculos',
        'max_fotos_por_vehiculo', 'incluye_certificacion',
        'vehiculos_destacados', 'badge_premium', 'features', 'activo',
        'stripe_price_id',
    ];

    public function tieneStripe(): bool
    {
        return ! empty($this->stripe_price_id);
    }

    protected $casts = [
        'incluye_certificacion' => 'boolean',
        'badge_premium'         => 'boolean',
        'activo'                => 'boolean',
        'features'              => 'array',
        'precio_mensual'        => 'decimal:2',
    ];

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class);
    }

    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format($this->precio_mensual, 0, '.', ',');
    }
}
