<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Al hacer login, sincroniza el tema guardado en BD con una cookie
 * que el script anti-flash puede leer en el lado del cliente.
 * También expone el tema del usuario a las vistas via View::share.
 */
class AplicarTema
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $tema = $request->user()->tema ?? 'system';

            // Comparte el tema con todas las vistas Blade
            view()->share('temaUsuario', $tema);

            // Si el tema en BD difiere del localStorage, inyecta un script
            // que lo corrija (se ejecuta una sola vez al cargar la página post-login)
            if ($tema !== 'system') {
                view()->share('temaScript', $tema);
            }
        }

        return $next($request);
    }
}
