<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestPlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\PruebaParticipante::firstOrCreate(
            ['telefono' => '0000000000'],
            [
                'nombre' => 'Piloto de Prueba',
                'token' => (string) \Illuminate\Support\Str::uuid(),
                'codigo_acceso' => 'TEST99',
                'saldo' => 9999999.00,
                'es_prueba' => true
            ]
        );
    }
}
