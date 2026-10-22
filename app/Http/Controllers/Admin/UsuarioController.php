<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('roles', 'agencia');

        if ($q = $request->get('q')) {
            $query->where(fn ($sq) =>
                $sq->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%")
            );
        }

        if ($rol = $request->get('rol')) {
            $query->role($rol);
        }

        if ($request->get('solo_inactivos')) {
            $query->where('activo', false);
        }

        $usuarios = $query->latest()->paginate(25)->withQueryString();

        $roles = Role::orderBy('name')->pluck('name');

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    public function create(): View
    {
        $roles    = Role::orderBy('name')->pluck('name');
        $agencias = Agencia::where('activo', true)->orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.usuarios.create', compact('roles', 'agencias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['nullable', 'string', 'min:8'],
            'rol'        => ['required', 'string', Rule::in(Role::pluck('name'))],
            'agencia_id' => ['nullable', 'exists:agencias,id'],
            'telefono'   => ['nullable', 'string', 'max:20'],
        ]);

        $password = $data['password'] ?? Str::random(10);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($password),
            'agencia_id' => $data['agencia_id'] ?? null,
            'telefono'   => $data['telefono'] ?? null,
            'activo'     => true,
        ]);

        $user->syncRoles([$data['rol']]);

        $msg = "Usuario <strong>{$user->name}</strong> creado con rol <strong>{$data['rol']}</strong>.";
        if (! $request->filled('password')) {
            $msg .= " Contraseña generada: <code>{$password}</code> — cópiala antes de salir.";
        }

        return redirect()->route('admin.usuarios.index')
            ->with('ok', $msg);
    }

    public function edit(User $usuario): View
    {
        $roles    = Role::orderBy('name')->pluck('name');
        $agencias = Agencia::where('activo', true)->orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.usuarios.edit', compact('usuario', 'roles', 'agencias'));
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($usuario->id)],
            'rol'        => ['required', 'string', Rule::in(Role::pluck('name'))],
            'agencia_id' => ['nullable', 'exists:agencias,id'],
            'telefono'   => ['nullable', 'string', 'max:20'],
        ]);

        $usuario->update([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'agencia_id' => $data['agencia_id'] ?? null,
            'telefono'   => $data['telefono'] ?? null,
        ]);

        $usuario->syncRoles([$data['rol']]);

        return redirect()->route('admin.usuarios.index')
            ->with('ok', "Usuario <strong>{$usuario->name}</strong> actualizado.");
    }

    public function resetPassword(User $usuario): RedirectResponse
    {
        $nueva = Str::random(10);
        $usuario->update(['password' => Hash::make($nueva)]);

        return back()->with('ok', "Nueva contraseña de <strong>{$usuario->name}</strong>: <code>{$nueva}</code> — cópiala antes de salir.");
    }

    public function toggleActivo(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivarte a ti mismo.');
        }

        $usuario->update(['activo' => ! $usuario->activo]);

        $estado = $usuario->activo ? 'activado' : 'desactivado';

        return back()->with('ok', "Usuario <strong>{$usuario->name}</strong> {$estado}.");
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $nombre = $usuario->name;
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('ok', "Usuario <strong>{$nombre}</strong> eliminado.");
    }
}
