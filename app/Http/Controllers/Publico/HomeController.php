<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Vehiculo;

class HomeController extends Controller
{
    public function index()
    {
        $destacados = Vehiculo::with(['fotoPrincipal', 'agencia', 'certificacion'])
            ->disponibles()
            ->destacados()
            ->latest()
            ->limit(6)
            ->get();

        // Si hay menos de 6 destacados, rellena con los más recientes
        if ($destacados->count() < 6) {
            $ids = $destacados->pluck('id')->toArray();
            $recientes = Vehiculo::with(['fotoPrincipal', 'agencia', 'certificacion'])
                ->disponibles()
                ->whereNotIn('id', $ids)
                ->latest()
                ->limit(6 - $destacados->count())
                ->get();
            $destacados = $destacados->concat($recientes);
        }

        $certificados = Vehiculo::with(['fotoPrincipal', 'agencia'])
            ->disponibles()
            ->certificados()
            ->latest()
            ->limit(4)
            ->get();

        $stats = [
            'vehiculos'   => Vehiculo::disponibles()->count(),
            'agencias'    => Agencia::where('activo', true)->count(),
            'certificados' => Vehiculo::disponibles()->certificados()->count(),
        ];

        return view('publico.home.index', compact('destacados', 'certificados', 'stats'));
    }
}
