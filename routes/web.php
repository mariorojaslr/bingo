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
use App\Http\Controllers\PilotoController;



/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Panel de Administración
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Organizadores
    Route::resource('organizadores', OrganizadorController::class)
        ->parameters(['organizadores' => 'organizador']);

    // Instituciones
    Route::resource('instituciones', InstitucionController::class)
        ->parameters(['instituciones' => 'institucion']);

    Route::put('instituciones/{institucion}/toggle', [InstitucionController::class, 'toggle'])
        ->name('instituciones.toggle');

    // Cartones
    Route::get('/cartones/generar', function () {
        return view('admin.cartones.generar');
    })->name('admin.cartones.generar');

    Route::post('/cartones/generar', [CartonController::class, 'generarCartones'])
        ->name('admin.cartones.store');

    Route::get('/cartones', [CartonController::class, 'listado'])
        ->name('admin.cartones.listado');

    // Impresión
    Route::get('/impresion', function () {
        return view('admin.impresion.formulario');
    })->name('admin.impresion.formulario');

    Route::post('/impresion/calcular', [CartonController::class, 'calcularLoteImpresion'])
        ->name('admin.impresion.calcular');

    Route::post('/impresion/generar-pdf', [CartonController::class, 'generarLotePDF'])
        ->name('admin.impresion.generar');

    // Jugadas
    Route::get('/jugadas', [JugadaController::class, 'index'])->name('admin.jugadas.index');
    Route::get('/jugadas/crear', [JugadaController::class, 'create'])->name('admin.jugadas.create');
    Route::post('/jugadas', [JugadaController::class, 'store'])->name('admin.jugadas.store');
    Route::get('/jugadas/{jugada}', [JugadaController::class, 'show'])->name('admin.jugadas.show');
    Route::get('/jugadas/{jugada}/cartones', [JugadaController::class, 'cartones'])->name('admin.jugadas.cartones');
    Route::post('/jugadas/{jugada}/lotes', [JugadaController::class, 'crearLote'])->name('admin.jugadas.lotes.crear');

    Route::post('/jugadas/{jugada}/asignar-cartones',
        [JugadaController::class, 'asignarCartonesMasivo'])
        ->name('admin.jugadas.asignarCartones');

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

    Route::post('/sorteo/sortear', [SorteoController::class, 'sortear'])
        ->name('admin.sorteo.sortear');
});

/*
|--------------------------------------------------------------------------
| Sorteador y Monitor (Tiempo Real)
|--------------------------------------------------------------------------
*/
Route::get('/sorteador/jugada/{jugada}', [SorteoController::class, 'ver'])
    ->name('sorteador.jugada');

Route::post('/sorteador/jugada/{jugada}/extraer', [SorteoController::class, 'extraer'])
    ->name('sorteador.extraer');

Route::post('/sorteador/jugada/{jugada}/continuar', [SorteoController::class, 'continuar'])
    ->name('sorteador.continuar');

Route::post('/sorteador/jugada/{jugada}/finalizar', [SorteoController::class, 'finalizar'])
    ->name('sorteador.finalizar');

Route::get('/monitor/jugada/{jugada}', [MonitorController::class, 'ver'])
    ->name('monitor.jugada');

Route::get('/api/monitor/jugada/{jugada}', [MonitorController::class, 'estado']);

/*
|--------------------------------------------------------------------------
| Vista Piloto (Participantes)
|--------------------------------------------------------------------------
*/
Route::get('/piloto/{token}', [PilotoController::class, 'ver'])
    ->name('piloto.ver');

    Route::prefix('sorteador')->name('sorteador.')->group(function () {
    Route::get('/jugada/{jugada}', [SorteoController::class, 'ver'])->name('jugada');
    Route::post('/jugada/{jugada}/extraer', [SorteoController::class, 'extraer'])->name('extraer');
    Route::post('/jugada/{jugada}/reanudar', [SorteoController::class, 'reanudar'])->name('reanudar');
    Route::post('/jugada/{jugada}/finalizar', [SorteoController::class, 'finalizar'])->name('finalizar');
});


Route::prefix('sorteador')->name('sorteador.')->group(function () {

    // Vista principal del sorteador
    Route::get('/jugada/{jugada}', [SorteoController::class, 'ver'])
        ->name('jugada');

    // Sacar bolilla (modo automático)
    Route::post('/jugada/{jugada}/extraer', [SorteoController::class, 'extraer'])
        ->name('extraer');

    // Confirmar línea (manual)
    Route::post('/jugada/{jugada}/confirmar-linea', [SorteoController::class, 'confirmarLinea'])
        ->name('confirmar.linea');

    // Reanudar juego luego de pagar línea
    Route::post('/jugada/{jugada}/reanudar', [SorteoController::class, 'reanudar'])
        ->name('reanudar');

    // Confirmar bingo (manual)
    Route::post('/jugada/{jugada}/confirmar-bingo', [SorteoController::class, 'confirmarBingo'])
        ->name('confirmar.bingo');

    // Finalizar completamente la jugada
    Route::post('/jugada/{jugada}/finalizar', [SorteoController::class, 'finalizar'])
        ->name('finalizar');
});

Route::prefix('sorteador')->name('sorteador.')->group(function () {

    Route::get('/jugada/{jugada}', [SorteoController::class, 'ver'])->name('jugada');

    Route::post('/jugada/{jugada}/extraer', [SorteoController::class, 'extraer'])->name('extraer');

    Route::post('/jugada/{jugada}/confirmar-linea', [SorteoController::class, 'confirmarLinea'])->name('confirmar.linea');

    Route::post('/jugada/{jugada}/reanudar', [SorteoController::class, 'reanudar'])->name('reanudar');

    Route::post('/jugada/{jugada}/confirmar-bingo', [SorteoController::class, 'confirmarBingo'])->name('confirmar.bingo');

    Route::post('/jugada/{jugada}/finalizar', [SorteoController::class, 'finalizar'])->name('finalizar');

    // 🔄 Nueva ruta
    Route::post('/jugada/{jugada}/reiniciar', [SorteoController::class, 'reiniciar'])->name('reiniciar');
});

Route::post('/sorteador/jugada/{jugada}/reiniciar', [App\Http\Controllers\Admin\SorteoController::class, 'reiniciar'])
    ->name('sorteador.reiniciar');
