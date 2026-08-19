<?php
namespace App\Http\Controllers\Agencia;

use App\Http\Controllers\Controller;
use App\Models\Certificacion;
use App\Models\Vehiculo;
use App\Models\Verificador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificacionController extends Controller
{
    public function index(): View
    {
        $agencia = auth()->user()->agencia;
        abort_unless($agencia, 403);

        $certificaciones = Certificacion::whereHas('vehiculo', fn($q) => $q->where('agencia_id', $agencia->id))
            ->with(['vehiculo', 'verificador'])
            ->latest()
            ->paginate(15);

        return view('agencia.certificaciones.index', compact('certificaciones'));
    }

    public function create(): View
    {
        $agencia = auth()->user()->agencia;
        abort_unless($agencia, 403);

        $vehiculos     = $agencia->vehiculos()->where('status', '!=', 'vendido')->orderBy('marca')->get();
        $verificadores = Verificador::where('activo', true)->orderBy('nombre')->get();

        return view('agencia.certificaciones.create', compact('vehiculos', 'verificadores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $agencia = auth()->user()->agencia;
        abort_unless($agencia, 403);

        $data = $request->validate([
            'vehiculo_id'      => ['required', 'exists:vehiculos,id'],
            'verificador_id'   => ['nullable', 'exists:verificadores,id'],
            'fecha_inspeccion' => ['required', 'date'],
            'observaciones'    => ['nullable', 'string', 'max:1000'],
        ]);

        $vehiculo = Vehiculo::where('id', $data['vehiculo_id'])
            ->where('agencia_id', $agencia->id)
            ->firstOrFail();

        Certificacion::create([
            'vehiculo_id'      => $vehiculo->id,
            'verificador_id'   => $data['verificador_id'] ?? null,
            'fecha_inspeccion' => $data['fecha_inspeccion'],
            'observaciones'    => $data['observaciones'] ?? null,
            'resultado'        => 'pendiente',
        ]);

        return redirect()->route('agencia.certificaciones.index')
            ->with('ok', 'Solicitud de certificación enviada. El administrador la revisará pronto.');
    }
}
