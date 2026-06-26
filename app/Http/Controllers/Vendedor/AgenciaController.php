<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgenciaController extends Controller
{
    public function index(): View
    {
        $agencias = auth()->user()
                          ->agenciasVendidas()
                          ->withCount('vehiculos')
                          ->latest()
                          ->paginate(20);

        return view('vendedor.agencias.index', compact('agencias'));
    }

    public function create(): View
    {
        return view('vendedor.agencias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'unique:agencias,email'],
            'telefono'  => ['required', 'string', 'max:20'],
            'ciudad'    => ['required', 'string', 'max:60'],
            'estado'    => ['required', 'string', 'max:60'],
            'whatsapp'  => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:200'],
        ]);

        $data['vendedor_id'] = auth()->id();
        $data['activo']      = false;
        $data['verificada']  = false;

        $agencia = Agencia::create($data);

        return redirect()
            ->route('vendedor.vehiculos.index', $agencia)
            ->with('ok', "Agencia \"{$agencia->nombre}\" registrada. Ahora captura su inventario.");
    }
}
