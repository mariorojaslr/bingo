<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

try {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    $token = (string) Str::uuid();

    DB::table('pruebas_participantes')->insert([
        'id' => 999,
        'nombre' => 'Piloto de Prueba',
        'token' => $token,
        'codigo_acceso' => 'TEST01',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('cartons')->insert([
        'id' => 999,
        'lote_impresion_id' => 1,
        'codigo' => 'CRT-TEST',
        'estado' => 'activo',
        'numeros' => json_encode([1,2,3,4,5,10,11,12,13,14,20,21,22,23,24]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('participante_carton_prueba')->insert([
        'participante_prueba_id' => 999,
        'carton_id' => 999,
        'jugada_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    echo "LINK_JUGADOR: https://fullbin.gentepiola.net/piloto/" . $token;
} catch (\Exception $e) {
    echo "ERROR:" . $e->getMessage();
}
