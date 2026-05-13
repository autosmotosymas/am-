<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que la agencia del usuario tenga una suscripción activa.
 * Si venció o no existe, redirige a la pantalla de renovación.
 * Solo aplica a rutas del portal de agencia.
 */
class CheckSuscripcionActiva
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole('agencia')) {
            return $next($request);
        }

        $agencia = $user->agencia;

        if (! $agencia) {
            return redirect()->route('perfil.index')
                ->with('error', 'No tienes una agencia asociada a tu cuenta.');
        }

        $suscripcionActiva = $agencia->suscripciones()
            ->where('status', 'activa')
            ->where('fecha_fin', '>=', now())
            ->exists();

        if (! $suscripcionActiva) {
            return redirect()->route('agencia.dashboard')
                ->with('suscripcion_vencida', true);
        }

        return $next($request);
    }
}
