<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgenciaController extends Controller
{
    public function show(\App\Models\Agencia $agencia)
    {
        abort_unless($agencia->activo, 404);

        $agencia->load(['vehiculos' => function ($q) {
            $q->disponibles()->with('fotoPrincipal')->latest()->limit(12);
        }]);

        return view('publico.agencia.show', compact('agencia'));
    }
}
