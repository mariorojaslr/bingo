<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $p = \App\Models\PruebaParticipante::firstOrCreate(
            ['telefono' => '0000000000'], 
            [
                'nombre' => 'PILOTO DEMO', 
                'token' => (string) \Illuminate\Support\Str::uuid(), 
                'codigo_acceso' => '123456', 
                'es_prueba' => true
            ]
        );
        $p->saldo_fichas = 50000;
        $p->save();
    }
}
