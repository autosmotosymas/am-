<?php

namespace App\Http\Controllers\Captura;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use App\Models\VehiculoFoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventarioController extends Controller
{
    public function index(): View
    {
        $vehiculos = Vehiculo::with(['fotoPrincipal', 'agencia'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('captura.index', compact('vehiculos'));
    }

    public function create(): View
    {
        $agencia = auth()->user()->agencia;
        abort_unless($agencia, 403, 'No tienes una agencia asignada. Contacta al administrador.');

        return view('captura.nuevo', compact('agencia'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user    = auth()->user();
        $agencia = $user->agencia;
        abort_unless($agencia, 403);

        $data = $request->validate([
            'tipo'        => ['required', 'in:auto,moto,camioneta,camion,otro'],
            'marca'       => ['required', 'string', 'max:60'],
            'modelo'      => ['required', 'string', 'max:60'],
            'anio'        => ['required', 'integer', 'min:1970', 'max:' . (date('Y') + 1)],
            'version'     => ['nullable', 'string', 'max:80'],
            'precio'      => ['required', 'numeric', 'min:0'],
            'kilometraje' => ['required', 'integer', 'min:0'],
            'transmision' => ['required', 'in:manual,automatica,cvt'],
            'combustible' => ['required', 'in:gasolina,diesel,electrico,hibrido,gas'],
            'color'       => ['required', 'string', 'max:40'],
            'vin'         => ['nullable', 'string', 'max:17'],
            'notas'       => ['nullable', 'string', 'max:1000'],
            'fotos'       => ['nullable', 'array', 'max:30'],
            'fotos.*'     => ['image', 'max:10240'],
        ]);

        $vehiculo = Vehiculo::create([
            'agencia_id'  => $agencia->id,
            'user_id'     => $user->id,
            'tipo'        => $data['tipo'],
            'marca'       => $data['marca'],
            'modelo'      => $data['modelo'],
            'anio'        => $data['anio'],
            'version'     => $data['version'] ?? null,
            'precio'      => $data['precio'],
            'kilometraje' => $data['kilometraje'],
            'transmision' => $data['transmision'],
            'combustible' => $data['combustible'],
            'color'       => $data['color'],
            'vin'         => $data['vin'] ?? null,
            'descripcion' => $data['notas'] ?? null,
            'status'      => 'inactivo', // borrador — agencia lo publica
            'ciudad'      => $agencia->ciudad,
            'estado'      => $agencia->estado,
        ]);

        if ($request->hasFile('fotos')) {
            $esPrimera = true;
            foreach ($request->file('fotos') as $i => $archivo) {
                $ruta = $archivo->store("vehiculos/{$vehiculo->id}", 'public');
                VehiculoFoto::create([
                    'vehiculo_id'  => $vehiculo->id,
                    'ruta'         => $ruta,
                    'orden'        => $i,
                    'es_principal' => $esPrimera,
                ]);
                $esPrimera = false;
            }
        }

        return redirect()
            ->route('captura.index')
            ->with('ok', "✓ {$vehiculo->anio} {$vehiculo->marca} {$vehiculo->modelo} capturado correctamente.");
    }
}
