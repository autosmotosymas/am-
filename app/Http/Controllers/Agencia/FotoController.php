<?php

namespace App\Http\Controllers\Agencia;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use App\Models\VehiculoFoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FotoController extends Controller
{
    private function agencia()
    {
        return auth()->user()->agencia;
    }

    private function autorizar(Vehiculo $vehiculo): void
    {
        $agencia = $this->agencia();
        abort_unless($agencia && $vehiculo->agencia_id === $agencia->id, 403);
    }

    public function index(Vehiculo $vehiculo): View
    {
        $this->autorizar($vehiculo);

        $fotos = $vehiculo->fotos()->orderBy('orden')->get();

        return view('agencia.vehiculos.fotos', compact('vehiculo', 'fotos'));
    }

    public function reordenar(Request $request, Vehiculo $vehiculo): JsonResponse
    {
        $this->autorizar($vehiculo);

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

    public function setPrincipal(Vehiculo $vehiculo, VehiculoFoto $foto): JsonResponse
    {
        $this->autorizar($vehiculo);

        $vehiculo->fotos()->update(['es_principal' => false]);
        $foto->update(['es_principal' => true]);

        return response()->json(['ok' => true]);
    }

    public function destroy(Vehiculo $vehiculo, VehiculoFoto $foto): JsonResponse
    {
        $this->autorizar($vehiculo);
        abort_unless($foto->vehiculo_id === $vehiculo->id, 403);

        if ($foto->es_principal) {
            $siguiente = $vehiculo->fotos()->where('id', '!=', $foto->id)->orderBy('orden')->first();
            $siguiente?->update(['es_principal' => true]);
        }

        \Storage::disk('public')->delete($foto->ruta);
        $foto->delete();

        return response()->json(['ok' => true]);
    }

    public function agregar(Request $request, Vehiculo $vehiculo): JsonResponse
    {
        $this->autorizar($vehiculo);

        $request->validate(['foto' => ['required', 'image', 'max:10240']]);

        $ultimoOrden = $vehiculo->fotos()->max('orden') ?? -1;
        $ruta = $request->file('foto')->store("vehiculos/{$vehiculo->id}", 'public');

        $foto = VehiculoFoto::create([
            'vehiculo_id'  => $vehiculo->id,
            'ruta'         => $ruta,
            'orden'        => $ultimoOrden + 1,
            'es_principal' => $vehiculo->fotos()->count() === 0,
        ]);

        return response()->json(['ok' => true, 'id' => $foto->id, 'url' => $foto->url]);
    }
}
