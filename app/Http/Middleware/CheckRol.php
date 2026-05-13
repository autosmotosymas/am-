<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que el usuario autenticado tenga al menos uno de los roles indicados.
 * Uso en rutas: middleware('rol:admin,agencia')
 * Spatie ya maneja 'role:X' pero este middleware da redirecciones
 * personalizadas según el rol del usuario en lugar de un 403 genérico.
 */
class CheckRol
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        foreach ($roles as $rol) {
            if ($request->user()->hasRole($rol)) {
                return $next($request);
            }
        }

        // Redirige al dashboard del rol que sí tiene
        return $this->redirigirSegunRol($request->user());
    }

    private function redirigirSegunRol($user): Response
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No tienes acceso a esa sección.');
        }

        if ($user->hasRole('agencia')) {
            return redirect()->route('agencia.dashboard')
                ->with('error', 'No tienes acceso a esa sección.');
        }

        if ($user->hasRole('capturador')) {
            return redirect()->route('captura.index')
                ->with('error', 'No tienes acceso a esa sección.');
        }

        return redirect()->route('perfil.index')
            ->with('error', 'No tienes acceso a esa sección.');
    }
}
