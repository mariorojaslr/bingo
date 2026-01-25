<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Bingo\CartonGenerator;
use App\Models\Carton;

class GenerarCartonesBingo extends Command
{
    /**
     * Nombre y firma del comando.
     * Se usará así:
     * php artisan bingo:generar-cartones {serie} {cantidad}
     */
    protected $signature = 'bingo:generar-cartones {serie} {cantidad}';

    /**
     * Descripción del comando.
     */
    protected $description = 'Genera cartones de bingo argentino y los guarda en la base de datos';

    /**
     * Ejecuta el comando.
     */
    public function handle()
    {
        $serie = $this->argument('serie');
        $cantidad = (int) $this->argument('cantidad');

        $this->info("Generando $cantidad cartones para la serie $serie...");

        $generator = new CartonGenerator();

        for ($i = 1; $i <= $cantidad; $i++) {
            $grilla = $generator->generarCartonArgentino();

            Carton::create([
                'serie' => $serie,
                'numero_carton' => $i,
                'formato' => 'argentino',
                'grilla' => json_encode($grilla),
                'estado' => 'disponible',
            ]);

            if ($i % 100 == 0) {
                $this->info("Cartones generados: $i");
            }
        }

        $this->info("Generación finalizada. Total: $cantidad cartones creados.");
    }
}
