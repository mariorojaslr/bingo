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
}
