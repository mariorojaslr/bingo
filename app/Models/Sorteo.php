<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sorteo extends Model
{
    protected $guarded = [];

    protected $casts = [
        'bolillas' => 'array',
    ];

    public function jugada()
    {
        return $this->belongsTo(Jugada::class);
    }

    /* =======================
     |  CICLO DE VIDA
     ======================= */

    public function iniciar(): void
    {
        $this->bolillas = [];
        $this->bolilla_actual = null;
        $this->estado = 'en_curso';
        $this->inicio = Carbon::now();
        $this->fin = null;
        $this->save();
    }

    public function finalizar(): void
    {
        $this->estado = 'finalizado';
        $this->fin = Carbon::now();
        $this->save();
    }

    /* =======================
     |  BOLILLAS
     ======================= */

    public function getBolillas(): array
    {
        return $this->bolillas ?? [];
    }

    public function agregarBolilla(int $numero): bool
    {
        $bolillas = $this->getBolillas();

        if (in_array($numero, $bolillas)) {
            return false;
        }

        $bolillas[] = $numero;

        $this->bolillas = $bolillas;
        $this->bolilla_actual = $numero;
        $this->save();

        return true;
    }

    /**
     * Retorna array con los IDs o Números de los cartones que tengan Linea o Bingo
     */
    public function evaluarGanadores(): array
    {
        $bolillas = $this->getMemoryBolillas();
        if (count($bolillas) < 5) return ['lineas' => [], 'bingos' => []];

        $lineas = [];
        $bingos = [];

        // 1. Obtener cartones fisicos
        $relacionesFisicas = \App\Models\JugadaCarton::where('jugada_id', $this->jugada_id)
                        ->with(['carton'])
                        ->get();
                        
        // 2. Obtener cartones digitales del piloto de prueba
        $relacionesPrueba = \App\Models\ParticipanteCartonPrueba::where('jugada_id', $this->jugada_id)
                        ->with(['carton'])
                        ->get();
                        
        // Juntar todos los cartones vendidos en esta jugada
        $todasLasRelaciones = $relacionesFisicas->concat($relacionesPrueba);
        
        foreach ($todasLasRelaciones as $rel) {
            $c = $rel->carton;
            if (!$c) continue;
            
            // Verificar bingo primero
            if ($c->esBingo($bolillas)) {
                $bingos[] = [
                    'numero' => $c->numero_carton,
                    'nombre' => 'Jugador #' . $c->numero_carton // En el futuro se puede mapear al nombre del participante si existe
                ];
            } elseif ($c->tieneLinea($bolillas)) {
                $lineas[] = [
                    'numero' => $c->numero_carton,
                    'nombre' => 'Jugador #' . $c->numero_carton
                ];
            }
        }

        return [
            'lineas' => $lineas,
            'bingos' => $bingos
        ];
    }

    /**
     * Cache local de bolillas para no parsear JSON mil veces por segundo
     */
    public function getMemoryBolillas(): array
    {
        return $this->bolillas ?? [];
    }
}
