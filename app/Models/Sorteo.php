<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sorteo extends Model
{
    use HasFactory;

    protected $table = 'sorteos';

    protected $fillable = [
        'jugada_id',
        'estado',
        'bolillas_sacadas',
        'bolilla_actual',
        'bolilla_linea',
        'bolilla_bingo',
        'inicio',
        'pausa',
        'fin',
        'tiempo_linea',
        'tiempo_bingo',
    ];

    /* =======================
       RELACIONES
    ======================= */

    public function jugada()
    {
        return $this->belongsTo(Jugada::class);
    }

    /* =======================
       BOLILLAS (TEXTO → ARRAY)
    ======================= */

    public function getBolillas(): array
    {
        if (!$this->bolillas_sacadas) {
            return [];
        }

        if (is_array($this->bolillas_sacadas)) {
            return $this->bolillas_sacadas;
        }

        $decoded = json_decode($this->bolillas_sacadas, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function setBolillas(array $bolillas): void
    {
        $this->bolillas_sacadas = json_encode(array_values($bolillas));
    }

    public function ultimaBolilla(): ?int
    {
        return $this->bolilla_actual;
    }

    /* =======================
       CICLO DE VIDA DEL SORTEO
    ======================= */

    public function iniciar(): void
    {
        $this->estado = 'en_curso';
        $this->inicio = Carbon::now();
        $this->bolilla_actual = null;
        $this->setBolillas([]);
        $this->save();
    }

    public function pausar(): void
    {
        $this->estado = 'pausado';
        $this->pausa = Carbon::now();
        $this->save();
    }

    public function finalizar(): void
    {
        $this->estado = 'finalizado';
        $this->fin = Carbon::now();
        $this->save();
    }

    /* =======================
       SORTEO DE BOLILLA
    ======================= */

    /**
     * @return bool true si se agregó, false si estaba repetida
     */
    public function agregarBolilla(int $numero): bool
    {
        $bolillas = $this->getBolillas();

        if (in_array($numero, $bolillas)) {
            return false;
        }

        $bolillas[] = $numero;

        $this->setBolillas($bolillas);
        $this->bolilla_actual = $numero;
        $this->save();

        return true;
    }

    /* =======================
       EVENTOS
    ======================= */

    public function registrarLinea(int $numeroBolilla): void
    {
        $this->bolilla_linea = $numeroBolilla;
        $this->tiempo_linea = Carbon::now()->diffInSeconds(
            Carbon::parse($this->inicio)
        );
        $this->estado = 'linea';
        $this->save();
    }

    public function registrarBingo(int $numeroBolilla): void
    {
        $this->bolilla_bingo = $numeroBolilla;
        $this->tiempo_bingo = Carbon::now()->diffInSeconds(
            Carbon::parse($this->inicio)
        );
        $this->estado = 'bingo';
        $this->fin = Carbon::now();
        $this->save();
    }
}
