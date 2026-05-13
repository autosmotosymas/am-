<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Verificador extends Model
{
    protected $table = 'verificadores';

    protected $fillable = ['nombre', 'email', 'telefono', 'zona', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function certificaciones(): HasMany
    {
        return $this->hasMany(Certificacion::class);
    }
}
