<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Excluir webhook de Stripe de validación CSRF
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);

        // AplicarTema se ejecuta en todas las rutas web para compartir el tema del usuario a las vistas
        $middleware->web(append: [
            \App\Http\Middleware\AplicarTema::class,
        ]);

        // Aliases para usar en rutas con parámetros
        $middleware->alias([
            'rol'                => \App\Http\Middleware\CheckRol::class,
            'suscripcion.activa' => \App\Http\Middleware\CheckSuscripcionActiva::class,
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
