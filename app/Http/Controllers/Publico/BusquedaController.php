<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehiculo::with(['fotoPrincipal', 'agencia', 'certificacion'])
            ->disponibles();

        // Búsqueda por texto
        if ($q = $request->get('q')) {
            $query->where(function ($q2) use ($q) {
                $q2->where('marca', 'like', "%{$q}%")
                   ->orWhere('modelo', 'like', "%{$q}%")
                   ->orWhere('version', 'like', "%{$q}%")
                   ->orWhere('anio', 'like', "%{$q}%");
            });
        }

        // Filtros
        if ($marca = $request->get('marca')) {
            $query->where('marca', $marca);
        }

        if ($tipo = $request->get('tipo')) {
            $query->where('tipo', $tipo);
        }

        if ($transmision = $request->get('transmision')) {
            $query->where('transmision', $transmision);
        }

        if ($combustible = $request->get('combustible')) {
            $query->where('combustible', $combustible);
        }

        if ($anio_min = $request->get('anio_min')) {
            $query->where('anio', '>=', (int) $anio_min);
        }

        if ($anio_max = $request->get('anio_max')) {
            $query->where('anio', '<=', (int) $anio_max);
        }

        if ($precio_min = $request->get('precio_min')) {
            $query->where('precio', '>=', (float) str_replace(',', '', $precio_min));
        }

        if ($precio_max = $request->get('precio_max')) {
            $query->where('precio', '<=', (float) str_replace(',', '', $precio_max));
        }

        if ($request->boolean('certificado')) {
            $query->certificados();
        }

        // Ordenamiento
        match ($request->get('orden', 'reciente')) {
            'precio_asc'  => $query->orderBy('precio'),
            'precio_desc' => $query->orderByDesc('precio'),
            'km_asc'      => $query->orderBy('kilometraje'),
            'anio_desc'   => $query->orderByDesc('anio'),
            default       => $query->orderByDesc('destacado')->orderByDesc('created_at'),
        };

        $vehiculos = $query->paginate(12)->withQueryString();

        // Opciones para los selects de filtro
        $marcas = Vehiculo::disponibles()->distinct()->orderBy('marca')->pluck('marca');
        $anioMin = Vehiculo::disponibles()->min('anio') ?? date('Y') - 15;
        $anioMax = Vehiculo::disponibles()->max('anio') ?? date('Y');

        return view('publico.busqueda.index', compact(
            'vehiculos', 'marcas', 'anioMin', 'anioMax'
        ));
    }

    public function marca(string $marca)
    {
        return redirect()->route('busqueda', ['marca' => $marca]);
    }

    public function tipo(string $tipo)
    {
        return redirect()->route('busqueda', ['tipo' => $tipo]);
    }
}
