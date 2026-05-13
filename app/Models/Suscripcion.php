<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suscripcion extends Model
{
    protected $fillable = [
        'agencia_id', 'plan_id', 'status',
        'fecha_inicio', 'fecha_vencimiento', 'precio_pagado',
        'stripe_subscription_id', 'stripe_customer_id', 'stripe_session_id',
    ];

    protected $casts = [
        'fecha_inicio'      => 'date',
        'fecha_vencimiento' => 'date',
        'precio_pagado'     => 'decimal:2',
    ];

    public function agencia(): BelongsTo
    {
        return $this->belongsTo(Agencia::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function estaActiva(): bool
    {
        return $this->status === 'activa' && $this->fecha_vencimiento->isFuture();
    }
}
