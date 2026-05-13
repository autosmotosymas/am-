<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function index(): View
    {
        $user = auth()->user()->load('leads.vehiculo');

        return view('perfil.index', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        // Cambio de contraseña opcional
        if ($request->filled('password')) {
            $request->validate([
                'password_actual' => ['required', function ($attr, $val, $fail) use ($user) {
                    if (! Hash::check($val, $user->password)) {
                        $fail('La contraseña actual es incorrecta.');
                    }
                }],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('guardado', true);
    }
}
