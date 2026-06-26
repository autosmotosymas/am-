<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Vehiculo;
use App\Models\VehiculoFoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    private function autorizarAgencia(Agencia $agencia): void
    {
        abort_unless(
            $agencia->vendedor_id === auth()->id(),
            403,
            'No tienes acceso a esta agencia.'
        );
    }

    public function index(Agencia $agencia): View
    {
        $this->autorizarAgencia($agencia);

        $vehiculos = $agencia->vehiculos()
                             ->with('fotoPrincipal')
                             ->latest()
                             ->paginate(20);

        return view('vendedor.vehiculos.index', compact('agencia', 'vehiculos'));
    }

    public function create(Agencia $agencia): View
    {
        $this->autorizarAgencia($agencia);

        $catalogo = config('catalogo');

        return view('vendedor.vehiculos.create', compact('agencia', 'catalogo'));
    }

    public function updateStatus(Request $request, Agencia $agencia, Vehiculo $vehiculo): RedirectResponse
    {
        $this->autorizarAgencia($agencia);

        $request->validate([
            'status' => ['required', 'in:disponible,inactivo,apartado,vendido'],
        ]);

        $vehiculo->update(['status' => $request->status]);

        return back()->with('ok', "Status actualizado a {$request->status}.");
    }

    public function store(Request $request, Agencia $agencia): RedirectResponse
    {
        $this->autorizarAgencia($agencia);

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
            'user_id'     => auth()->id(),
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
            'status'      => 'inactivo',
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
            ->route('vendedor.vehiculos.index', $agencia)
            ->with('ok', "{$vehiculo->anio} {$vehiculo->marca} {$vehiculo->modelo} capturado.");
    }
}
