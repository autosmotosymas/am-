<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'agencia_id', 'suscripcion_id', 'monto', 'metodo',
        'stripe_payment_intent_id', 'referencia', 'status', 'fecha_pago',
    ];

    protected $casts = [
        'monto'      => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    public function agencia(): BelongsTo
    {
        return $this->belongsTo(Agencia::class);
    }

    public function suscripcion(): BelongsTo
    {
        return $this->belongsTo(Suscripcion::class);
    }
}
