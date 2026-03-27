<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartonController;
use App\Http\Controllers\Admin\JugadaController;
use App\Http\Controllers\Admin\VisorController;
use App\Http\Controllers\Admin\LoteController;
use App\Http\Controllers\Admin\OrganizadorController;
use App\Http\Controllers\Admin\InstitucionController;
use App\Http\Controllers\Admin\SorteoController;
use App\Http\Controllers\Admin\MonitorController;
use App\Http\Controllers\Admin\PruebasController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PilotoController;

/*
|--------------------------------------------------------------------------
| Rutas Administrativas y Panel Owner
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // 1. ÁTICO DEL OWNER
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // 2. ASCENSOR TELETRANSPORTADOR
    Route::get('/admin/impersonate/{organizador}', [\App\Http\Controllers\Admin\ImpersonateController::class, 'enter'])->name('admin.impersonate');
    Route::get('/admin/impersonate/leave/casino', [\App\Http\Controllers\Admin\ImpersonateController::class, 'leave'])->name('admin.leave_impersonation');

    // 3. PISO DE LA FRANQUICIA (Panel del Cliente)
    Route::get('/franquicia/dashboard', function () {
        // Validación de Seguridad: Solo entras si eres el Dueño real del Tenant o el Owner en modo "Fantasma"
        if (!session('impersonating_organizador_id') && auth()->user()->email === 'mario.rojas.coach@gmail.com') {
            return redirect()->route('admin.dashboard')->with('error', 'Debes entrar bajando por el ascensor de franquicias.');
        }

        // Devolvemos TU ANTIGUO DASHBOARD exactamente como te gustaba.
        return view('admin.dashboard');
    })->name('tenant.dashboard');
});

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Autenticación Premium (Login y Passkeys)
|--------------------------------------------------------------------------
*/
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/auth/biometric', [\App\Http\Controllers\AuthController::class, 'showBiometric'])->name('auth.biometric');
Route::post('/auth/biometric/verify', [\App\Http\Controllers\AuthController::class, 'verifyBiometric'])->name('auth.biometric.verify');

// Rutas de Control Remoto de Email (OTP)
Route::get('/auth/otp', [\App\Http\Controllers\AuthController::class, 'showOtp'])->name('auth.otp.show');
Route::post('/auth/otp', [\App\Http\Controllers\AuthController::class, 'verifyOtp'])->name('auth.otp.verify');

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Lobby y Venta)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/salas', [\App\Http\Controllers\LobbyController::class, 'index'])->name('lobby.index');

Route::get('/tienda/{jugada}', [\App\Http\Controllers\UserStoreController::class, 'showTienda'])->name('tienda.show');
Route::post('/tienda/{jugada}', [\App\Http\Controllers\UserStoreController::class, 'procesarCompra'])->name('tienda.procesar');
Route::get('/tienda/gracias/{token}', [\App\Http\Controllers\UserStoreController::class, 'gracias'])->name('tienda.gracias');

/*
|--------------------------------------------------------------------------
| Panel de Administración
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    // La ruta base de /admin redirige automáticamente vía el enrutado de arriba a /admin/dashboard

    // Organizadores
    Route::resource('organizadores', OrganizadorController::class)
        ->parameters(['organizadores' => 'organizador']);

    // Instituciones
    Route::resource('instituciones', InstitucionController::class)
        ->parameters(['instituciones' => 'institucion']);

    Route::put('instituciones/{institucion}/toggle',
        [InstitucionController::class, 'toggle']
    )->name('instituciones.toggle');

    // Cartones
    Route::get('/cartones/generar', fn() =>
        view('admin.cartones.generar')
    )->name('admin.cartones.generar');

    Route::post('/cartones/generar',
        [CartonController::class, 'generarCartones']
    )->name('admin.cartones.store');

    Route::get('/cartones',
        [CartonController::class, 'listado']
    )->name('admin.cartones.listado');

    // Impresión
    Route::get('/impresion', fn() =>
        view('admin.impresion.formulario')
    )->name('admin.impresion.formulario');

    Route::post('/impresion/calcular',
        [CartonController::class, 'calcularLoteImpresion']
    )->name('admin.impresion.calcular');

    Route::post('/impresion/generar-pdf',
        [CartonController::class, 'generarLotePDF']
    )->name('admin.impresion.generar');

    // Jugadas
    Route::get('/jugadas', [JugadaController::class, 'index'])->name('admin.jugadas.index');
    Route::get('/jugadas/crear', [JugadaController::class, 'create'])->name('admin.jugadas.create');
    Route::post('/jugadas', [JugadaController::class, 'store'])->name('admin.jugadas.store');
    Route::get('/jugadas/{jugada}', [JugadaController::class, 'show'])->name('admin.jugadas.show');
    Route::get('/jugadas/{jugada}/cartones', [JugadaController::class, 'cartones'])->name('admin.jugadas.cartones');
    Route::post('/jugadas/{jugada}/lotes', [JugadaController::class, 'crearLote'])->name('admin.jugadas.lotes.crear');

    Route::post('/jugadas/{jugada}/asignar-cartones',
        [JugadaController::class, 'asignarCartonesMasivo']
    )->name('admin.jugadas.asignarCartones');

    Route::post('/jugadas/{jugada}/streaming',
        [JugadaController::class, 'updateStreaming']
    )->name('admin.jugadas.streaming.update');

    // Lotes
    Route::post('/lotes/{lote}/generar', [LoteController::class, 'generar'])->name('admin.lotes.generar');
    Route::post('/lotes/{lote}/materializar', [LoteController::class, 'materializar'])->name('admin.lotes.materializar');

    // Visor de impresión
    Route::get('/visor/lote/{lote}', [VisorController::class, 'verLote'])->name('admin.visor.lote');

    // Pruebas
    Route::prefix('pruebas')->name('admin.pruebas.')->group(function () {
        Route::get('/', [PruebasController::class, 'index'])->name('index');
        Route::get('/participantes', [PruebasController::class, 'participantes'])->name('participantes');
        Route::post('/participantes', [PruebasController::class, 'storeParticipante'])->name('participantes.store');
        Route::get('/jugadas', [PruebasController::class, 'jugadas'])->name('jugadas');
    });
});

/*
|--------------------------------------------------------------------------
| Sorteador (Tiempo Real)
|--------------------------------------------------------------------------
*/
Route::prefix('sorteador')->name('sorteador.')->group(function () {

    Route::get('/jugada/{jugada}', [SorteoController::class, 'ver'])->name('jugada');
    Route::post('/jugada/{jugada}/extraer', [SorteoController::class, 'extraer'])->name('extraer');
    Route::post('/jugada/{jugada}/confirmar-linea', [SorteoController::class, 'confirmarLinea'])->name('confirmar.linea');
    Route::post('/jugada/{jugada}/reanudar', [SorteoController::class, 'reanudar'])->name('reanudar');
    Route::post('/jugada/{jugada}/confirmar-bingo', [SorteoController::class, 'confirmarBingo'])->name('confirmar.bingo');
    Route::post('/jugada/{jugada}/finalizar', [SorteoController::class, 'finalizar'])->name('finalizar');
    Route::post('/jugada/{jugada}/reiniciar', [SorteoController::class, 'reiniciar'])->name('reiniciar');
});

/*
|--------------------------------------------------------------------------
| Monitor (Clásico)
|--------------------------------------------------------------------------
*/
Route::get('/monitor/jugada/{jugada}', [MonitorController::class, 'ver'])
    ->name('monitor.jugada');

Route::get('/api/monitor/jugada/{jugada}', [MonitorController::class, 'estado']);

/*
|--------------------------------------------------------------------------
| Monitor TV (Pantalla Pública)
|--------------------------------------------------------------------------
*/
Route::get('/monitor-tv', function () {
    return redirect()->route('admin.jugadas.index')->with('error', 'Debes seleccionar una sala.');
});

Route::get('/monitor-tv/{jugada}', function ($jugadaId) {
    $sorteo = \App\Models\Sorteo::where('jugada_id', $jugadaId)
        ->latest()
        ->firstOrFail();

    return view('monitor.monitor-tv', compact('sorteo', 'jugadaId'));
});

/*
|--------------------------------------------------------------------------
| Vista Piloto (Participantes)
|--------------------------------------------------------------------------
*/
Route::get('/piloto/{token}', [PilotoController::class, 'ver'])
    ->name('piloto.ver');
