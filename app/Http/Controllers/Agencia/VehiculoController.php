<?php

namespace App\Http\Controllers\Agencia;

use App\Http\Controllers\Controller;
use App\Jobs\NotificarCambioPrecio;
use App\Jobs\NotificarCambioStatus;
use App\Models\Vehiculo;
use App\Models\VehiculoFoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    private function agencia()
    {
        return auth()->user()->agencia;
    }

    public function index(): View
    {
        $agencia = $this->agencia();
        abort_unless($agencia, 403);

        $vehiculos = $agencia->vehiculos()
            ->with('fotoPrincipal')
            ->latest()
            ->paginate(15);

        $plan = $agencia->suscripcionActiva?->plan;

        return view('agencia.vehiculos.index', compact('vehiculos', 'agencia', 'plan'));
    }

    public function create(): View
    {
        $agencia = $this->agencia();
        abort_unless($agencia, 403);

        $plan = $agencia->suscripcionActiva?->plan;

        // Verificar límite del plan
        $activos = $agencia->vehiculos()->whereIn('status', ['disponible', 'inactivo'])->count();
        $limite  = $plan?->max_vehiculos ?? 20;

        return view('agencia.vehiculos.form', compact('agencia', 'plan', 'activos', 'limite'));
    }

    public function store(Request $request): RedirectResponse
    {
        $agencia = $this->agencia();
        abort_unless($agencia, 403);

        $data = $this->validar($request);
        $data['agencia_id'] = $agencia->id;
        $data['user_id']    = auth()->id();
        $data['status']     = 'disponible';

        $vehiculo = Vehiculo::create($data);

        $this->guardarFotos($request, $vehiculo);

        return redirect()->route('agencia.vehiculos.index')
            ->with('ok', 'Vehículo publicado correctamente.');
    }

    public function edit(Vehiculo $vehiculo): View
    {
        $agencia = $this->agencia();
        $this->autorizar($vehiculo, $agencia);

        $vehiculo->load('fotos');
        $plan = $agencia->suscripcionActiva?->plan;

        return view('agencia.vehiculos.form', compact('vehiculo', 'agencia', 'plan'));
    }

    public function update(Request $request, Vehiculo $vehiculo): RedirectResponse
    {
        $agencia = $this->agencia();
        $this->autorizar($vehiculo, $agencia);

        $data = $this->validar($request);

        $precioAnterior = (float) $vehiculo->precio;
        $statusAnterior = $vehiculo->status;

        $vehiculo->update($data);

        // Notificar cambio de precio (solo si bajó)
        if (isset($data['precio']) && (float) $data['precio'] < $precioAnterior) {
            NotificarCambioPrecio::dispatch($vehiculo, $precioAnterior, (float) $data['precio']);
        }

        // Notificar cambio de status a apartado/vendido
        if (isset($data['status']) && $data['status'] !== $statusAnterior) {
            NotificarCambioStatus::dispatch($vehiculo, $statusAnterior, $data['status']);
        }

        $this->guardarFotos($request, $vehiculo);

        // Eliminar fotos marcadas para borrar
        if ($request->has('fotos_eliminar')) {
            foreach ($request->fotos_eliminar as $fotoId) {
                $foto = VehiculoFoto::where('id', $fotoId)
                    ->where('vehiculo_id', $vehiculo->id)
                    ->first();
                if ($foto) {
                    Storage::disk('public')->delete($foto->ruta);
                    $foto->delete();
                }
            }
        }

        return redirect()->route('agencia.vehiculos.index')
            ->with('ok', 'Vehículo actualizado.');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        $agencia = $this->agencia();
        $this->autorizar($vehiculo, $agencia);

        // Eliminar fotos del disco
        foreach ($vehiculo->fotos as $foto) {
            Storage::disk('public')->delete($foto->ruta);
        }

        $vehiculo->delete();

        return redirect()->route('agencia.vehiculos.index')
            ->with('ok', 'Vehículo eliminado.');
    }

    // ── Helpers ─────────────────────────────────────────────────

    private function validar(Request $request): array
    {
        return $request->validate([
            'tipo'              => ['required', 'in:auto,moto,camioneta,camion,otro'],
            'marca'             => ['required', 'string', 'max:60'],
            'modelo'            => ['required', 'string', 'max:60'],
            'anio'              => ['required', 'integer', 'min:1970', 'max:' . (date('Y') + 1)],
            'version'           => ['nullable', 'string', 'max:80'],
            'precio'            => ['required', 'numeric', 'min:0'],
            'precio_negociable' => ['boolean'],
            'kilometraje'       => ['required', 'integer', 'min:0'],
            'transmision'       => ['required', 'in:manual,automatica,cvt'],
            'combustible'       => ['required', 'in:gasolina,diesel,electrico,hibrido,gas'],
            'color'             => ['required', 'string', 'max:40'],
            'puertas'           => ['nullable', 'integer', 'min:2', 'max:6'],
            'cilindros'         => ['nullable', 'integer', 'min:1', 'max:16'],
            'motor'             => ['nullable', 'string', 'max:40'],
            'descripcion'       => ['nullable', 'string', 'max:2000'],
            'ciudad'            => ['nullable', 'string', 'max:60'],
            'estado'            => ['nullable', 'string', 'max:60'],
            'vin'               => ['nullable', 'string', 'max:17'],
            'placas'            => ['nullable', 'string', 'max:10'],
            'status'            => ['nullable', 'in:disponible,inactivo,apartado,vendido'],
            'destacado'         => ['boolean'],
            'fotos'             => ['nullable', 'array', 'max:30'],
            'fotos.*'           => ['image', 'max:5120'], // 5 MB por foto
        ]);
    }

    private function guardarFotos(Request $request, Vehiculo $vehiculo): void
    {
        if (! $request->hasFile('fotos')) return;

        $orden = $vehiculo->fotos()->max('orden') ?? -1;
        $esPrimera = $vehiculo->fotos()->count() === 0;

        foreach ($request->file('fotos') as $archivo) {
            $ruta = $archivo->store("vehiculos/{$vehiculo->id}", 'public');
            $orden++;

            VehiculoFoto::create([
                'vehiculo_id'  => $vehiculo->id,
                'ruta'         => $ruta,
                'orden'        => $orden,
                'es_principal' => $esPrimera,
            ]);

            $esPrimera = false;
        }
    }

    private function autorizar(Vehiculo $vehiculo, $agencia): void
    {
        abort_unless($agencia && $vehiculo->agencia_id === $agencia->id, 403);
    }
}
