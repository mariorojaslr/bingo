<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jugada;
use App\Models\Sorteo;
use Carbon\Carbon;

class RunVirtualBingo extends Command
{
    protected $signature = 'bingo:run-games';
    protected $description = 'Daemon que extrae bolillas automáticamente para las salas de bingo virtuales en curso.';

    public function handle()
    {
        $this->info("Iniciando motor de Bingo Virtual...");

        while (true) {
            $now = Carbon::now();

            // 1. Buscar salas creadas que ya deberían empezar
            $salasPorEmpezar = Jugada::whereNotNull('plantilla_sala_virtual_id')
                ->where('estado', 'creada')
                ->where(function($q) use ($now) {
                    $q->where('fecha_evento', '<', $now->toDateString())
                      ->orWhere(function($q2) use ($now) {
                          $q2->where('fecha_evento', '=', $now->toDateString())
                             ->where('hora_evento', '<=', $now->toTimeString());
                      });
                })
                ->get();

            foreach ($salasPorEmpezar as $sala) {
                $sala->update(['estado' => 'en_curso']);
                if ($sala->sorteo) {
                    $sala->sorteo->iniciar();
                    $this->info("Iniciando sorteo de la sala: {$sala->nombre_jugada}");
                }
            }

            // 2. Extraer bolillas para las salas en curso
            $sorteosEnCurso = Sorteo::where('estado', 'en_curso')->get();

            foreach ($sorteosEnCurso as $sorteo) {
                // Verificar si ya pasaron X segundos desde el inicio o la última bolilla
                // Por simplicidad en este daemon de 3 segundos, sacamos una bolilla cada iteración (cada 5 seg)
                // En un caso real podrías guardar 'ultimo_sorteo_at' y calcular la diferencia.

                $numeroRandom = rand(1, 90);
                
                // Evitar loop infinito si ya salieron todas (90 bolillas)
                $bolillas = $sorteo->getBolillas();
                if (count($bolillas) >= 90) {
                    $sorteo->finalizar();
                    $sorteo->jugada->update(['estado' => 'jugada']);
                    continue;
                }

                // Sacar número único
                while (in_array($numeroRandom, $bolillas)) {
                    $numeroRandom = rand(1, 90);
                }

                $sorteo->agregarBolilla($numeroRandom);
                $this->info("Sorteo #{$sorteo->id}: Salió la bolilla {$numeroRandom} (Total: " . (count($bolillas)+1) . ")");

                // Evaluar ganadores
                $ganadores = $sorteo->evaluarGanadores();

                if (!empty($ganadores['bingos'])) {
                    $this->info("¡BINGO en el sorteo #{$sorteo->id}!");
                    $sorteo->finalizar();
                    $sorteo->jugada->update(['estado' => 'jugada']);
                    
                    // Aquí habría que registrar el ganador en la DB y asignar saldo/pozo
                    // usando $sorteo->jugada->pozo_acumulado si salio antes del limite_bolilla_pozo
                }
            }

            // Dormir 4 segundos (el ciclo total será aprox 5 seg)
            sleep(4);
        }
    }
}
