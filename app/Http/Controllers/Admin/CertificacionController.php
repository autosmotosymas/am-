<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificacion;
use App\Models\Vehiculo;
use App\Models\Verificador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificacionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Certificacion::with(['vehiculo.agencia', 'verificador'])
            ->latest();

        if ($resultado = $request->get('resultado')) {
            $query->where('resultado', $resultado);
        }

        $certificaciones = $query->paginate(20)->withQueryString();

        $conteos = [
            'pendiente' => Certificacion::where('resultado', 'pendiente')->count(),
            'aprobado'  => Certificacion::where('resultado', 'aprobado')->count(),
            'rechazado' => Certificacion::where('resultado', 'rechazado')->count(),
        ];

        return view('admin.certificaciones.index', compact('certificaciones', 'conteos'));
    }

    public function create(): View
    {
        $vehiculos    = Vehiculo::disponibles()->with('agencia')->orderBy('marca')->get();
        $verificadores = Verificador::where('activo', true)->orderBy('nombre')->get();

        return view('admin.certificaciones.create', compact('vehiculos', 'verificadores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vehiculo_id'     => ['required', 'exists:vehiculos,id'],
            'verificador_id'  => ['required', 'exists:verificadores,id'],
            'fecha_inspeccion'=> ['required', 'date'],
            'resultado'       => ['required', 'in:pendiente,aprobado,rechazado'],
            'puntaje'         => ['nullable', 'integer', 'min:0', 'max:100'],
            'observaciones'   => ['nullable', 'string', 'max:2000'],
        ]);

        Certificacion::create($data);

        return redirect()->route('admin.certificaciones.index')
            ->with('ok', 'Certificación registrada.');
    }

    public function show(Certificacion $certificacione): View
    {
        $certificacione->load(['vehiculo.agencia', 'verificador']);

        return view('admin.certificaciones.show', ['cert' => $certificacione]);
    }

    public function aprobar(Certificacion $certificacione): RedirectResponse
    {
        $certificacione->update(['resultado' => 'aprobado']);

        return back()->with('ok', 'Certificación aprobada.');
    }

    public function rechazar(Request $request, Certificacion $certificacione): RedirectResponse
    {
        $request->validate(['observaciones' => ['required', 'string', 'max:500']]);

        $certificacione->update([
            'resultado'     => 'rechazado',
            'observaciones' => $request->observaciones,
        ]);

        return back()->with('ok', 'Certificación rechazada.');
    }

    public function edit(Certificacion $certificacione): View
    {
        $verificadores = Verificador::where('activo', true)->get();

        return view('admin.certificaciones.edit', [
            'cert'         => $certificacione,
            'verificadores'=> $verificadores,
        ]);
    }

    public function update(Request $request, Certificacion $certificacione): RedirectResponse
    {
        $data = $request->validate([
            'verificador_id'  => ['required', 'exists:verificadores,id'],
            'fecha_inspeccion'=> ['required', 'date'],
            'resultado'       => ['required', 'in:pendiente,aprobado,rechazado'],
            'puntaje'         => ['nullable', 'integer', 'min:0', 'max:100'],
            'observaciones'   => ['nullable', 'string', 'max:2000'],
        ]);

        $certificacione->update($data);

        return redirect()->route('admin.certificaciones.index')
            ->with('ok', 'Certificación actualizada.');
    }

    public function destroy(Certificacion $certificacione): RedirectResponse
    {
        $certificacione->delete();

        return redirect()->route('admin.certificaciones.index')
            ->with('ok', 'Certificación eliminada.');
    }
}
