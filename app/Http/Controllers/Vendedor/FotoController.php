<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Vehiculo;
use App\Models\VehiculoFoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FotoController extends Controller
{
    private function autorizar(Agencia $agencia): void
    {
        abort_unless($agencia->vendedor_id === auth()->id(), 403);
    }

    public function index(Agencia $agencia, Vehiculo $vehiculo): View
    {
        $this->autorizar($agencia);

        $fotos = $vehiculo->fotos()->orderBy('orden')->get();

        return view('vendedor.vehiculos.fotos', compact('agencia', 'vehiculo', 'fotos'));
    }

    public function reordenar(Request $request, Agencia $agencia, Vehiculo $vehiculo): JsonResponse
    {
        $this->autorizar($agencia);

        $request->validate([
            'orden'   => ['required', 'array'],
            'orden.*' => ['integer', 'exists:vehiculo_fotos,id'],
        ]);

        foreach ($request->orden as $posicion => $fotoId) {
            VehiculoFoto::where('id', $fotoId)
                        ->where('vehiculo_id', $vehiculo->id)
                        ->update(['orden' => $posicion]);
        }

        return response()->json(['ok' => true]);
    }

    public function setPrincipal(Agencia $agencia, Vehiculo $vehiculo, VehiculoFoto $foto): JsonResponse
    {
        $this->autorizar($agencia);

        // Quitar principal de todas
        $vehiculo->fotos()->update(['es_principal' => false]);
        // Poner en la seleccionada
        $foto->update(['es_principal' => true]);

        return response()->json(['ok' => true]);
    }

    public function destroy(Agencia $agencia, Vehiculo $vehiculo, VehiculoFoto $foto): JsonResponse
    {
        $this->autorizar($agencia);

        abort_unless($foto->vehiculo_id === $vehiculo->id, 403);

        // Si era principal, asignar la siguiente
        if ($foto->es_principal) {
            $siguiente = $vehiculo->fotos()->where('id', '!=', $foto->id)->orderBy('orden')->first();
            $siguiente?->update(['es_principal' => true]);
        }

        \Storage::disk('public')->delete($foto->ruta);
        $foto->delete();

        return response()->json(['ok' => true]);
    }

    public function agregar(Request $request, Agencia $agencia, Vehiculo $vehiculo): JsonResponse
    {
        $this->autorizar($agencia);

        $request->validate([
            'foto'  => ['required', 'image', 'max:10240'],
        ]);

        $ultimoOrden = $vehiculo->fotos()->max('orden') ?? -1;
        $ruta = $request->file('foto')->store("vehiculos/{$vehiculo->id}", 'public');

        $foto = VehiculoFoto::create([
            'vehiculo_id'  => $vehiculo->id,
            'ruta'         => $ruta,
            'orden'        => $ultimoOrden + 1,
            'es_principal' => $vehiculo->fotos()->count() === 0,
        ]);

        return response()->json([
            'ok'  => true,
            'id'  => $foto->id,
            'url' => $foto->url,
        ]);
    }
}
