<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartonController;
use App\Http\Controllers\Admin\JugadaController;
use App\Http\Controllers\Admin\VisorController;
use App\Http\Controllers\Admin\LoteController;

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
| Acceso por /admin/...
*/

Route::prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Módulo: Cartones
    |--------------------------------------------------------------------------
    */
    Route::get('/cartones/generar', function () {
        return view('admin.cartones.generar');
    })->name('admin.cartones.generar');

    Route::post('/cartones/generar', [CartonController::class, 'generarCartones'])
        ->name('admin.cartones.store');

    Route::get('/cartones', [CartonController::class, 'listado'])
        ->name('admin.cartones.listado');

    /*
    |--------------------------------------------------------------------------
    | Módulo: Impresión
    |--------------------------------------------------------------------------
    */
    Route::get('/impresion', function () {
        return view('admin.impresion.formulario');
    })->name('admin.impresion.formulario');

    Route::post('/impresion/calcular', [CartonController::class, 'calcularLoteImpresion'])
        ->name('admin.impresion.calcular');

    Route::post('/impresion/generar-pdf', [CartonController::class, 'generarLotePDF'])
        ->name('admin.impresion.generar');

    /*
    |--------------------------------------------------------------------------
    | Módulo: Jugadas
    |--------------------------------------------------------------------------
    */

    // Listado
    Route::get('/jugadas', [JugadaController::class, 'index'])
        ->name('admin.jugadas.index');

    // Crear jugada
    Route::get('/jugadas/crear', [JugadaController::class, 'create'])
        ->name('admin.jugadas.create');

    Route::post('/jugadas', [JugadaController::class, 'store'])
        ->name('admin.jugadas.store');

    // Ver jugada
    Route::get('/jugadas/{jugada}', [JugadaController::class, 'show'])
        ->name('admin.jugadas.show');

    // Visor global de cartones por jugada
    Route::get('/jugadas/{jugada}/cartones', [JugadaController::class, 'cartones'])
        ->name('admin.jugadas.cartones');

    // Crear pedido de lote
    Route::post('/jugadas/{jugada}/lotes', [JugadaController::class, 'crearLote'])
        ->name('admin.jugadas.lotes.crear');

    /*
    |--------------------------------------------------------------------------
    | Flujo de Lotes: Pedido → Generado → Materializado → Visor
    |--------------------------------------------------------------------------
    */

    // Paso 1: Pedido → Generado
    Route::post('/lotes/{lote}/generar', [LoteController::class, 'generar'])
        ->name('admin.lotes.generar');

    // Paso 2: Generado → Materializado (crear cartones físicos)
    Route::post('/lotes/{lote}/materializar', [LoteController::class, 'materializar'])
        ->name('admin.lotes.materializar');

    // Paso 3: Visor por lote (solo materializados)
    Route::get('/visor/lote/{lote}', [VisorController::class, 'verLote'])
        ->name('admin.visor.lote');

    Route::post('/admin/lotes/{lote}/materializar', [LoteController::class, 'materializar'])
        ->name('admin.lotes.materializar');


});
