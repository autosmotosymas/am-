<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verificador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificadorController extends Controller
{
    public function index(): View
    {
        $verificadores = Verificador::withCount('certificaciones')
            ->latest()->paginate(20);

        return view('admin.verificadores.index', compact('verificadores'));
    }

    public function create(): View
    {
        return view('admin.verificadores.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:verificadores,email'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'zona'     => ['nullable', 'string', 'max:100'],
        ]);

        Verificador::create($data);

        return redirect()->route('admin.verificadores.index')
            ->with('ok', 'Verificador registrado.');
    }

    public function edit(Verificador $verificadore): View
    {
        return view('admin.verificadores.form', ['verificador' => $verificadore]);
    }

    public function update(Request $request, Verificador $verificadore): RedirectResponse
    {
        $data = $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', "unique:verificadores,email,{$verificadore->id}"],
            'telefono' => ['nullable', 'string', 'max:20'],
            'zona'     => ['nullable', 'string', 'max:100'],
            'activo'   => ['boolean'],
        ]);

        $verificadore->update($data);

        return redirect()->route('admin.verificadores.index')
            ->with('ok', 'Verificador actualizado.');
    }

    public function destroy(Verificador $verificadore): RedirectResponse
    {
        $verificadore->delete();

        return redirect()->route('admin.verificadores.index')
            ->with('ok', 'Verificador eliminado.');
    }

    public function show(Verificador $verificadore): View
    {
        $verificadore->load(['certificaciones.vehiculo.agencia']);

        return view('admin.verificadores.show', ['verificador' => $verificadore]);
    }
}
