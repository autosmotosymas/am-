<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $fillable = ['user_id', 'tipo', 'titulo', 'mensaje', 'leida', 'data'];

    protected $casts = [
        'leida' => 'boolean',
        'data'  => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
