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
        $bolillas = $this->getBolillas();
        if (count($bolillas) < 5) return ['lineas' => [], 'bingos' => []];

        $lineas = [];
        $bingos = [];

        // Obtener cartones de prueba (si los hay)
        $cartonesPrueba = \App\Models\ParticipanteCartonPrueba::where('jugada_id', $this->jugada_id)
                            ->with('carton')->get()->pluck('carton');
                            
        // TODO: Agregar consulta de cartones reales impresos si interfiere el modelo
        
        foreach ($cartonesPrueba as $c) {
            if (!$c) continue;
            
            // Verificar bingo primero
            if ($c->esBingo($bolillas)) {
                $bingos[] = $c->numero_carton;
            } elseif ($c->tieneLinea($bolillas)) {
                $lineas[] = $c->numero_carton;
            }
        }

        return [
            'lineas' => array_unique($lineas),
            'bingos' => array_unique($bingos)
        ];
    }
}
