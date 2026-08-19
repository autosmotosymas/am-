<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Publico\HomeController;
use App\Http\Controllers\Publico\BusquedaController;
use App\Http\Controllers\Publico\VehiculoController as PublicoVehiculoController;
use App\Http\Controllers\Publico\AgenciaController as PublicoAgenciaController;
use App\Http\Controllers\Publico\LeadController as PublicoLeadController;
use App\Http\Controllers\Publico\ContactoController;
use App\Http\Controllers\Perfil\PerfilController;
use App\Http\Controllers\Perfil\TemaController;
use App\Http\Controllers\Agencia\DashboardController as AgenciaDashboard;
use App\Http\Controllers\Agencia\VehiculoController as AgenciaVehiculoController;
use App\Http\Controllers\Agencia\LeadController as AgenciaLeadController;
use App\Http\Controllers\Agencia\CertificacionController as AgenciaCertificacionController;
use App\Http\Controllers\Agencia\EstadisticasController;
use App\Http\Controllers\Agencia\SuscripcionController as AgenciaSuscripcionController;
use App\Http\Controllers\Stripe\WebhookController as StripeWebhookController;
use App\Http\Controllers\Captura\InventarioController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\AgenciaController as AdminAgenciaController;
use App\Http\Controllers\Admin\VerificadorController;
use App\Http\Controllers\Admin\CertificacionController;
use App\Http\Controllers\Admin\SuscripcionController;
use App\Http\Controllers\Admin\VendedorController;
use App\Http\Controllers\Vendedor\DashboardController as VendedorDashboard;
use App\Http\Controllers\Vendedor\AgenciaController as VendedorAgenciaController;
use App\Http\Controllers\Vendedor\VehiculoController as VendedorVehiculoController;
use App\Http\Controllers\Vendedor\PerfilController as VendedorPerfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cara pública
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/aviso-de-privacidad', fn() => view('publico.legal.privacidad'))->name('privacidad');
Route::get('/terminos-y-condiciones', fn() => view('publico.legal.terminos'))->name('terminos');
Route::get('/busqueda', [BusquedaController::class, 'index'])->name('busqueda');
Route::get('/autos/{vehiculo:slug}', [PublicoVehiculoController::class, 'show'])->name('vehiculo.show');
Route::get('/agencias/{agencia:slug}', [PublicoAgenciaController::class, 'show'])->name('agencia.show');
Route::get('/autos/marca/{marca}', [BusquedaController::class, 'marca'])->name('busqueda.marca');
Route::get('/autos/tipo/{tipo}', [BusquedaController::class, 'tipo'])->name('busqueda.tipo');
Route::post('/leads', [PublicoLeadController::class, 'store'])->name('lead.store');
Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

/*
|--------------------------------------------------------------------------
| Perfil del comprador (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('perfil')->name('perfil.')->group(function () {
    Route::get('/', [PerfilController::class, 'index'])->name('index');
    Route::put('/', [PerfilController::class, 'update'])->name('update');
    Route::post('/tema', [TemaController::class, 'store'])->name('tema');
});

/*
|--------------------------------------------------------------------------
| Portal agencia (auth + rol agencia)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:agencia'])->prefix('agencia')->name('agencia.')->group(function () {
    Route::get('/dashboard', [AgenciaDashboard::class, 'index'])->name('dashboard');
    Route::resource('vehiculos', AgenciaVehiculoController::class);
    Route::get('/leads', [AgenciaLeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/export', [AgenciaLeadController::class, 'export'])->name('leads.export');
    Route::patch('/leads/{lead}/leer', [AgenciaLeadController::class, 'marcarLeido'])->name('leads.leer');
    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas');

    // Gestión de fotos (agencia)
    Route::get('/vehiculos/{vehiculo}/fotos', [\App\Http\Controllers\Agencia\FotoController::class, 'index'])->name('vehiculos.fotos.index');
    Route::post('/vehiculos/{vehiculo}/fotos', [\App\Http\Controllers\Agencia\FotoController::class, 'agregar'])->name('vehiculos.fotos.agregar');
    Route::patch('/vehiculos/{vehiculo}/fotos/reordenar', [\App\Http\Controllers\Agencia\FotoController::class, 'reordenar'])->name('vehiculos.fotos.reordenar');
    Route::patch('/vehiculos/{vehiculo}/fotos/{foto}/principal', [\App\Http\Controllers\Agencia\FotoController::class, 'setPrincipal'])->name('vehiculos.fotos.principal');
    Route::delete('/vehiculos/{vehiculo}/fotos/{foto}', [\App\Http\Controllers\Agencia\FotoController::class, 'destroy'])->name('vehiculos.fotos.destroy');

    // Certificaciones
    Route::get('/certificaciones', [AgenciaCertificacionController::class, 'index'])->name('certificaciones.index');
    Route::get('/certificaciones/solicitar', [AgenciaCertificacionController::class, 'create'])->name('certificaciones.create');
    Route::post('/certificaciones', [AgenciaCertificacionController::class, 'store'])->name('certificaciones.store');

    // Suscripción / Stripe Checkout
    Route::get('/suscripcion', [AgenciaSuscripcionController::class, 'index'])->name('suscripcion.index');
    Route::post('/suscripcion/checkout', [AgenciaSuscripcionController::class, 'checkout'])->name('suscripcion.checkout');
    Route::get('/suscripcion/exito', [AgenciaSuscripcionController::class, 'exito'])->name('suscripcion.exito');
    Route::post('/suscripcion/cancelar', [AgenciaSuscripcionController::class, 'cancelar'])->name('suscripcion.cancelar');
});

/*
|--------------------------------------------------------------------------
| Webhooks de Stripe (sin CSRF)
|--------------------------------------------------------------------------
*/
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| App de captura móvil (auth + rol capturador)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:capturador'])->prefix('captura')->name('captura.')->group(function () {
    Route::get('/', [InventarioController::class, 'index'])->name('index');
    Route::get('/nuevo', [InventarioController::class, 'create'])->name('create');
    Route::post('/', [InventarioController::class, 'store'])->name('store');
    Route::get('/{vehiculo}/editar', [InventarioController::class, 'edit'])->name('edit');
    Route::put('/{vehiculo}', [InventarioController::class, 'update'])->name('update');
    Route::delete('/{vehiculo}', [InventarioController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| App de vendedor (auth + rol vendedor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:vendedor'])->prefix('vendedor')->name('vendedor.')->group(function () {
    Route::get('/dashboard', [VendedorDashboard::class, 'index'])->name('dashboard');
    Route::get('/perfil', [VendedorPerfilController::class, 'index'])->name('perfil');
    Route::put('/perfil', [VendedorPerfilController::class, 'update'])->name('perfil.update');

    Route::get('/agencias', [VendedorAgenciaController::class, 'index'])->name('agencias.index');
    Route::get('/agencias/nueva', [VendedorAgenciaController::class, 'create'])->name('agencias.create');
    Route::post('/agencias', [VendedorAgenciaController::class, 'store'])->name('agencias.store');
    Route::get('/agencias/exito', [VendedorAgenciaController::class, 'exito'])->name('agencias.exito');
    Route::get('/agencias/{agencia}', [VendedorAgenciaController::class, 'show'])->name('agencias.show');
    Route::put('/agencias/{agencia}', [VendedorAgenciaController::class, 'update'])->name('agencias.update');
    Route::post('/agencias/{agencia}/checkout', [VendedorAgenciaController::class, 'checkout'])->name('agencias.checkout');

    Route::get('/agencias/{agencia}/vehiculos', [VendedorVehiculoController::class, 'index'])->name('vehiculos.index');
    Route::get('/agencias/{agencia}/vehiculos/nuevo', [VendedorVehiculoController::class, 'create'])->name('vehiculos.create');
    Route::post('/agencias/{agencia}/vehiculos', [VendedorVehiculoController::class, 'store'])->name('vehiculos.store');
    Route::get('/agencias/{agencia}/vehiculos/{vehiculo}/editar', [VendedorVehiculoController::class, 'edit'])->name('vehiculos.edit');
    Route::put('/agencias/{agencia}/vehiculos/{vehiculo}', [VendedorVehiculoController::class, 'update'])->name('vehiculos.update');
    Route::patch('/agencias/{agencia}/vehiculos/{vehiculo}/status', [VendedorVehiculoController::class, 'updateStatus'])->name('vehiculos.status');

    // Gestión de fotos
    Route::get('/agencias/{agencia}/vehiculos/{vehiculo}/fotos', [\App\Http\Controllers\Vendedor\FotoController::class, 'index'])->name('fotos.index');
    Route::post('/agencias/{agencia}/vehiculos/{vehiculo}/fotos', [\App\Http\Controllers\Vendedor\FotoController::class, 'agregar'])->name('fotos.agregar');
    Route::patch('/agencias/{agencia}/vehiculos/{vehiculo}/fotos/reordenar', [\App\Http\Controllers\Vendedor\FotoController::class, 'reordenar'])->name('fotos.reordenar');
    Route::patch('/agencias/{agencia}/vehiculos/{vehiculo}/fotos/{foto}/principal', [\App\Http\Controllers\Vendedor\FotoController::class, 'setPrincipal'])->name('fotos.principal');
    Route::delete('/agencias/{agencia}/vehiculos/{vehiculo}/fotos/{foto}', [\App\Http\Controllers\Vendedor\FotoController::class, 'destroy'])->name('fotos.destroy');
});

/*
|--------------------------------------------------------------------------
| Panel admin (auth + rol admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('agencias', AdminAgenciaController::class);
    Route::patch('agencias/{agencia}/toggle', [AdminAgenciaController::class, 'toggleActivo'])->name('agencias.toggle');
    Route::patch('agencias/{agencia}/verificar', [AdminAgenciaController::class, 'verificar'])->name('agencias.verificar');
    Route::post('agencias/{agencia}/suscribir', [AdminAgenciaController::class, 'suscribir'])->name('agencias.suscribir');
    Route::post('agencias/bulk', [AdminAgenciaController::class, 'bulkAction'])->name('agencias.bulk');

    Route::resource('verificadores', VerificadorController::class);

    Route::resource('certificaciones', CertificacionController::class);
    Route::patch('certificaciones/{certificacione}/aprobar', [CertificacionController::class, 'aprobar'])->name('certificaciones.aprobar');
    Route::patch('certificaciones/{certificacione}/rechazar', [CertificacionController::class, 'rechazar'])->name('certificaciones.rechazar');

    Route::resource('suscripciones', SuscripcionController::class);
    Route::patch('suscripciones/{suscripcione}/cancelar', [SuscripcionController::class, 'cancelar'])->name('suscripciones.cancelar');
    Route::get('suscripciones-export', [SuscripcionController::class, 'export'])->name('suscripciones.export');
    Route::get('agencias-export', [AdminAgenciaController::class, 'export'])->name('agencias.export');

    Route::resource('vendedores', VendedorController::class)
         ->parameters(['vendedores' => 'vendedor'])
         ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Preview de mails — solo entorno local
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::prefix('__preview/mail')->group(function () {
        Route::get('verificar-email', function () {
            $user = \App\Models\User::latest()->first();
            return (new \App\Notifications\VerificarEmailNotification)
                ->toMail($user)
                ->render();
        });

        Route::get('reset-password', function () {
            $user = \App\Models\User::latest()->first();
            return (new \App\Notifications\ResetPasswordNotification('fake-token-123'))
                ->toMail($user)
                ->render();
        });

        Route::get('agencia-bienvenida', function () {
            $agencia = \App\Models\Agencia::latest()->first()
                ?? new \App\Models\Agencia(['nombre' => 'Agencia de Autos Fulanito', 'email' => 'agencia@ejemplo.com']);

            return new \App\Mail\AgenciaBienvenidaMail($agencia, 'MiPassword123');
        });

        Route::get('suscripcion-activada', function () {
            $suscripcion = \App\Models\Suscripcion::with('plan', 'agencia')->latest()->first()
                ?? new \App\Models\Suscripcion(['fecha_inicio' => now(), 'fecha_vencimiento' => now()->addMonths(3), 'precio_pagado' => 5500]);

            if (! $suscripcion->plan) {
                $suscripcion->setRelation('plan', new \App\Models\Plan(['nombre' => 'Base', 'duracion' => 'trimestral', 'meses' => 3]));
            }
            if (! $suscripcion->agencia) {
                $suscripcion->setRelation('agencia', new \App\Models\Agencia(['nombre' => 'Agencia de Autos Fulanito']));
            }

            return new \App\Mail\SuscripcionActivadaMail($suscripcion);
        });

        Route::get('renovacion-cobrada', function () {
            $suscripcion = \App\Models\Suscripcion::with('plan', 'agencia')->latest()->first()
                ?? new \App\Models\Suscripcion(['fecha_vencimiento' => now()->addMonths(3)]);

            if (! $suscripcion->plan) {
                $suscripcion->setRelation('plan', new \App\Models\Plan(['nombre' => 'Base', 'duracion' => 'trimestral', 'meses' => 3]));
            }
            if (! $suscripcion->agencia) {
                $suscripcion->setRelation('agencia', new \App\Models\Agencia(['nombre' => 'Agencia de Autos Fulanito']));
            }

            return new \App\Mail\RenovacionCobradaMail($suscripcion, 5500.00);
        });

        Route::get('pago-fallido', function () {
            $suscripcion = \App\Models\Suscripcion::with('plan', 'agencia')->latest()->first()
                ?? new \App\Models\Suscripcion([]);

            if (! $suscripcion->plan) {
                $suscripcion->setRelation('plan', new \App\Models\Plan(['nombre' => 'Base', 'duracion' => 'trimestral']));
            }
            if (! $suscripcion->agencia) {
                $suscripcion->setRelation('agencia', new \App\Models\Agencia(['nombre' => 'Agencia de Autos Fulanito']));
            }

            return new \App\Mail\PagoFallidoMail($suscripcion, 5500.00);
        });

        Route::get('suscripcion-cancelada', function () {
            $suscripcion = \App\Models\Suscripcion::with('plan', 'agencia')->latest()->first()
                ?? new \App\Models\Suscripcion(['fecha_vencimiento' => now()->addMonths(2)]);

            if (! $suscripcion->plan) {
                $suscripcion->setRelation('plan', new \App\Models\Plan(['nombre' => 'Base', 'duracion' => 'trimestral']));
            }
            if (! $suscripcion->agencia) {
                $suscripcion->setRelation('agencia', new \App\Models\Agencia(['nombre' => 'Agencia de Autos Fulanito']));
            }

            return new \App\Mail\SuscripcionCanceladaMail($suscripcion);
        });

        Route::get('renovacion-proxima', function () {
            $suscripcion = \App\Models\Suscripcion::with('plan', 'agencia')->latest()->first()
                ?? new \App\Models\Suscripcion([
                    'plan_id'           => null,
                    'fecha_vencimiento' => now()->addDays(7),
                ]);

            // Datos de ejemplo si no hay suscripción real
            if (! $suscripcion->plan) {
                $suscripcion->setRelation('plan', new \App\Models\Plan([
                    'nombre'   => 'Base',
                    'duracion' => 'trimestral',
                    'meses'    => 3,
                    'precio'   => 5500,
                ]));
            }
            if (! $suscripcion->agencia) {
                $suscripcion->setRelation('agencia', new \App\Models\Agencia([
                    'nombre' => 'Agencia de Autos Fulanito',
                ]));
            }

            return new \App\Mail\RenovacionProximaMail($suscripcion, 5500.00, '26/08/2026');
        });

        Route::get('suscripcion-vence', function () {
            $suscripcion = \App\Models\Suscripcion::with('plan', 'agencia')->latest()->first()
                ?? new \App\Models\Suscripcion(['fecha_vencimiento' => now()->addDays(5)]);

            if (! $suscripcion->plan) {
                $suscripcion->setRelation('plan', new \App\Models\Plan(['nombre' => 'Base', 'duracion' => 'trimestral']));
            }
            if (! $suscripcion->agencia) {
                $suscripcion->setRelation('agencia', new \App\Models\Agencia(['nombre' => 'Agencia de Autos Fulanito']));
            }

            return new \App\Mail\SuscripcionVenceMail($suscripcion, 5);
        });

        Route::get('nuevo-lead', function () {
            $lead = \App\Models\Lead::with('vehiculo', 'agencia')->latest()->first();

            if (! $lead) {
                $vehiculo = new \App\Models\Vehiculo(['marca' => 'Toyota', 'modelo' => 'Corolla', 'anio' => 2022]);
                $lead = new \App\Models\Lead([
                    'nombre'   => 'Juan Pérez',
                    'telefono' => '33 1234 5678',
                    'tipo'     => 'whatsapp',
                    'mensaje'  => '¿Está disponible? Me interesa verlo este fin de semana.',
                ]);
                $lead->setRelation('vehiculo', $vehiculo);
            }

            return new \App\Mail\NuevoLeadMail($lead);
        });

        Route::get('cambio-precio', function () {
            $vehiculo = \App\Models\Vehiculo::latest()->first()
                ?? new \App\Models\Vehiculo(['marca' => 'Honda', 'modelo' => 'Civic', 'anio' => 2021, 'precio' => 180000, 'slug' => 'honda-civic-2021']);

            return new \App\Mail\CambioPrecioMail($vehiculo, 200000, 180000);
        });

        Route::get('cambio-status', function () {
            $vehiculo = \App\Models\Vehiculo::latest()->first()
                ?? new \App\Models\Vehiculo(['marca' => 'Honda', 'modelo' => 'Civic', 'anio' => 2021, 'slug' => 'honda-civic-2021']);

            return new \App\Mail\CambioStatusMail($vehiculo, 'vendido');
        });
    });
}

require __DIR__.'/auth.php';
