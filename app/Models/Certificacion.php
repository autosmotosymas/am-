<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificacion extends Model
{
    protected $table = 'certificaciones';

    protected $fillable = [
        'vehiculo_id', 'verificador_id', 'fecha_inspeccion',
        'resultado', 'puntaje', 'observaciones', 'reporte_pdf',
    ];

    protected $casts = [
        'fecha_inspeccion' => 'date',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(Verificador::class);
    }

    public function estaAprobada(): bool
    {
        return $this->resultado === 'aprobado';
    }
}
