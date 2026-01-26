<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartonController;
use App\Http\Controllers\Admin\JugadaController;
use App\Http\Controllers\Admin\VisorController;
use App\Http\Controllers\Admin\LoteController;
use App\Http\Controllers\Admin\OrganizadorController;
use App\Http\Controllers\Admin\InstitucionController;

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

    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Organizadores (parámetro corregido)
    Route::resource('organizadores', OrganizadorController::class)
        ->parameters(['organizadores' => 'organizador']);

    // Instituciones (parámetro corregido)
    Route::resource('instituciones', InstitucionController::class)
        ->parameters(['instituciones' => 'institucion']);

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
    Route::get('/jugadas', [JugadaController::class, 'index'])
        ->name('admin.jugadas.index');

    Route::get('/jugadas/crear', [JugadaController::class, 'create'])
        ->name('admin.jugadas.create');

    Route::post('/jugadas', [JugadaController::class, 'store'])
        ->name('admin.jugadas.store');

    Route::get('/jugadas/{jugada}', [JugadaController::class, 'show'])
        ->name('admin.jugadas.show');

    Route::get('/jugadas/{jugada}/cartones', [JugadaController::class, 'cartones'])
        ->name('admin.jugadas.cartones');

    Route::post('/jugadas/{jugada}/lotes', [JugadaController::class, 'crearLote'])
        ->name('admin.jugadas.lotes.crear');

    // Lotes
    Route::post('/lotes/{lote}/generar', [LoteController::class, 'generar'])
        ->name('admin.lotes.generar');

    Route::post('/lotes/{lote}/materializar', [LoteController::class, 'materializar'])
        ->name('admin.lotes.materializar');

    Route::get('/visor/lote/{lote}', [VisorController::class, 'verLote'])
        ->name('admin.visor.lote');
});
