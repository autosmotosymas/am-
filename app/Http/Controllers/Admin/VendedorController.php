<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class VendedorController extends Controller
{
    public function index(): View
    {
        $vendedores = User::role('vendedor')
                          ->withCount('agenciasVendidas')
                          ->latest()
                          ->paginate(20);

        return view('admin.vendedores.index', compact('vendedores'));
    }

    public function create(): View
    {
        return view('admin.vendedores.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'telefono'              => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'telefono' => $data['telefono'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('vendedor');

        return redirect()->route('admin.vendedores.index')
            ->with('ok', "Vendedor {$user->name} creado.");
    }

    public function edit(User $vendedor): View
    {
        abort_unless($vendedor->hasRole('vendedor'), 404);

        $vendedor->load('agenciasVendidas.vehiculos');

        return view('admin.vendedores.show', compact('vendedor'));
    }

    public function update(Request $request, User $vendedor): RedirectResponse
    {
        abort_unless($vendedor->hasRole('vendedor'), 404);

        $rules = [
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email,' . $vendedor->id],
            'telefono' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        $data = $request->validate($rules);

        $vendedor->name     = $data['name'];
        $vendedor->email    = $data['email'];
        $vendedor->telefono = $data['telefono'] ?? null;

        if (!empty($data['password'])) {
            $vendedor->password = Hash::make($data['password']);
        }

        $vendedor->save();

        return redirect()->route('admin.vendedores.show', $vendedor)
            ->with('ok', 'Vendedor actualizado.');
    }

    public function show(User $vendedor): View
    {
        abort_unless($vendedor->hasRole('vendedor'), 404);

        $vendedor->load('agenciasVendidas.vehiculos');

        return view('admin.vendedores.show', compact('vendedor'));
    }

    public function destroy(User $vendedor): RedirectResponse
    {
        abort_unless($vendedor->hasRole('vendedor'), 404);

        $nombre = $vendedor->name;
        $vendedor->delete();

        return redirect()->route('admin.vendedores.index')
            ->with('ok', "Vendedor {$nombre} eliminado.");
    }
}
