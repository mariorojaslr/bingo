<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PlantillaSalaVirtual;
use App\Models\Jugada;
use App\Models\Sorteo;
use Carbon\Carbon;

class GenerarSalasVirtualesCommand extends Command
{
    protected $signature = 'bingo:generar-salas';
    protected $description = 'Genera las salas virtuales (Jugadas) a partir de las plantillas activas';

    public function handle()
    {
        $plantillas = PlantillaSalaVirtual::where('activo', true)->get();
        $now = Carbon::now();

        foreach ($plantillas as $plantilla) {
            // Buscamos la última jugada creada para esta plantilla
            $ultimaJugada = Jugada::where('plantilla_sala_virtual_id', $plantilla->id)
                                  ->orderBy('fecha_evento', 'desc')
                                  ->orderBy('hora_evento', 'desc')
                                  ->first();

            if ($ultimaJugada) {
                // La próxima sala debería ser a la hora de la última + el intervalo
                $ultimaFechaHora = Carbon::parse($ultimaJugada->fecha_evento . ' ' . $ultimaJugada->hora_evento);
                $proximaFechaHora = $ultimaFechaHora->copy()->addMinutes($plantilla->intervalo_minutos);
            } else {
                // Si no hay ninguna, la primera será ahora mismo (redondeando hacia arriba si queremos)
                $proximaFechaHora = $now->copy();
            }

            // Generamos salas hasta cubrir las próximas 2 horas por ejemplo
            $limiteGeneracion = $now->copy()->addHours(2);

            while ($proximaFechaHora->lessThanOrEqualTo($limiteGeneracion)) {
                
                // Si la próxima sala está en el pasado (por algún problema), la adelantamos a 'ahora'
                if ($proximaFechaHora->lessThan($now)) {
                    $proximaFechaHora = $now->copy();
                }

                // Buscar un organizador e institucion válidos por defecto
                $organizador = \App\Models\Organizador::first();
                $institucion = \App\Models\Institucion::first();

                // Creamos la Jugada (Sala)
                $jugada = Jugada::create([
                    'empresa_id' => $plantilla->empresa_id,
                    'plantilla_sala_virtual_id' => $plantilla->id,
                    'organizador_id' => $organizador ? $organizador->id : 1, 
                    'institucion_id' => $institucion ? $institucion->id : 1, 
                    'nombre_jugada' => $plantilla->nombre . ' - ' . $proximaFechaHora->format('H:i'),
                    'fecha_evento' => $proximaFechaHora->toDateString(),
                    'hora_evento' => $proximaFechaHora->toTimeString(),
                    'precio_hoja' => $plantilla->precio_carton,
                    'pozo_acumulado' => 0, // Inicia en 0, el pozo arrastrado se calcula al final de la anterior
                    'limite_bolilla_pozo' => $plantilla->limite_bolilla_pozo,
                    'estado' => 'creada' // 'creada' o un estado para "venta abierta"
                ]);

                // Creamos su sorteo vacío asociado
                Sorteo::create([
                    'jugada_id' => $jugada->id,
                    'estado' => 'pendiente'
                ]);

                $this->info("Sala creada: {$jugada->nombre_jugada} para {$proximaFechaHora}");

                $proximaFechaHora->addMinutes($plantilla->intervalo_minutos);
            }
        }

        return 0;
    }
}
