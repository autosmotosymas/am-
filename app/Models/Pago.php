<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'agencia_id', 'suscripcion_id', 'monto', 'metodo',
        'stripe_payment_intent_id', 'referencia', 'status', 'fecha_pago',
    ];
}
