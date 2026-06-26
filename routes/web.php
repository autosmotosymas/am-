<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Publico\HomeController;
use App\Http\Controllers\Publico\BusquedaController;
use App\Http\Controllers\Publico\VehiculoController as PublicoVehiculoController;
use App\Http\Controllers\Publico\AgenciaController as PublicoAgenciaController;
use App\Http\Controllers\Publico\LeadController as PublicoLeadController;
use App\Http\Controllers\Perfil\PerfilController;
use App\Http\Controllers\Perfil\TemaController;
use App\Http\Controllers\Agencia\DashboardController as AgenciaDashboard;
use App\Http\Controllers\Agencia\VehiculoController as AgenciaVehiculoController;
use App\Http\Controllers\Agencia\LeadController as AgenciaLeadController;
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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cara pública
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/busqueda', [BusquedaController::class, 'index'])->name('busqueda');
Route::get('/autos/{vehiculo:slug}', [PublicoVehiculoController::class, 'show'])->name('vehiculo.show');
Route::get('/agencias/{agencia:slug}', [PublicoAgenciaController::class, 'show'])->name('agencia.show');
Route::get('/autos/marca/{marca}', [BusquedaController::class, 'marca'])->name('busqueda.marca');
Route::get('/autos/tipo/{tipo}', [BusquedaController::class, 'tipo'])->name('busqueda.tipo');
Route::post('/leads', [PublicoLeadController::class, 'store'])->name('lead.store');

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
    Route::patch('/leads/{lead}/leer', [AgenciaLeadController::class, 'marcarLeido'])->name('leads.leer');
    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas');

    // Suscripción / Stripe Checkout
    Route::get('/suscripcion', [AgenciaSuscripcionController::class, 'index'])->name('suscripcion.index');
    Route::post('/suscripcion/checkout', [AgenciaSuscripcionController::class, 'checkout'])->name('suscripcion.checkout');
    Route::get('/suscripcion/exito', [AgenciaSuscripcionController::class, 'exito'])->name('suscripcion.exito');
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
});

/*
|--------------------------------------------------------------------------
| App de vendedor (auth + rol vendedor)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:vendedor'])->prefix('vendedor')->name('vendedor.')->group(function () {
    Route::get('/dashboard', [VendedorDashboard::class, 'index'])->name('dashboard');

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

    Route::resource('verificadores', VerificadorController::class);

    Route::resource('certificaciones', CertificacionController::class);
    Route::patch('certificaciones/{certificacione}/aprobar', [CertificacionController::class, 'aprobar'])->name('certificaciones.aprobar');
    Route::patch('certificaciones/{certificacione}/rechazar', [CertificacionController::class, 'rechazar'])->name('certificaciones.rechazar');

    Route::resource('suscripciones', SuscripcionController::class);
    Route::patch('suscripciones/{suscripcione}/cancelar', [SuscripcionController::class, 'cancelar'])->name('suscripciones.cancelar');

    Route::resource('vendedores', VendedorController::class)
         ->only(['index', 'create', 'store', 'show', 'destroy']);
});

require __DIR__.'/auth.php';
