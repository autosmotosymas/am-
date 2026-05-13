<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;

class VehiculoController extends Controller
{
    public function show(Vehiculo $vehiculo)
    {
        // Solo mostrar vehículos publicados (no borrador, no vendido)
        abort_if(
            in_array($vehiculo->status, ['inactivo']),
            404
        );

        // Cargar relaciones necesarias para la ficha
        $vehiculo->load([
            'fotos',
            'agencia',
            'certificacion.verificador',
        ]);

        // Incrementar vistas (sin disparar eventos de modelo)
        Vehiculo::withoutTimestamps(fn () =>
            $vehiculo->increment('vistas')
        );

        // Vehículos relacionados de la misma agencia (mismo tipo/marca)
        $relacionados = Vehiculo::with('fotoPrincipal')
            ->disponibles()
            ->where('id', '!=', $vehiculo->id)
            ->where(function ($q) use ($vehiculo) {
                $q->where('agencia_id', $vehiculo->agencia_id)
                  ->orWhere('marca', $vehiculo->marca);
            })
            ->limit(4)
            ->get();

        return view('publico.vehiculo.show', compact('vehiculo', 'relacionados'));
    }
}
