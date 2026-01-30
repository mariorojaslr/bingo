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
        'tiempo_linea',
        'tiempo_bingo',
        'inicio_sorteo',
        'fin_sorteo',
        'premio_linea',
        'premio_bingo'
    ];

    // ================= RELACIONES =================

    public function jugada()
    {
        return $this->belongsTo(Jugada::class);
    }

    // ================= NORMALIZACIÓN INTERNA =================

    public function getBolillas(): array
    {
        if (empty($this->bolillas_sacadas)) {
            return [];
        }

        if (is_array($this->bolillas_sacadas)) {
            return $this->bolillas_sacadas;
        }

        if (is_string($this->bolillas_sacadas)) {
            $decoded = json_decode($this->bolillas_sacadas, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    public function setBolillas(array $bolillas): void
    {
        $this->bolillas_sacadas = json_encode(array_values($bolillas));
    }

    // ================= LÓGICA DE NEGOCIO =================

    public function iniciar(): void
    {
        $this->estado = 'en_curso';
        $this->inicio_sorteo = Carbon::now();
        $this->setBolillas([]);
        $this->save();
    }

    public function pausar(): void
    {
        $this->estado = 'pausado';
        $this->save();
    }

    public function reanudar(): void
    {
        $this->estado = 'en_curso';
        $this->save();
    }

    public function finalizar(): void
    {
        $this->estado = 'finalizado';
        $this->fin_sorteo = Carbon::now();
        $this->save();
    }

    public function agregarBolilla(int $numero): void
    {
        $bolillas = $this->getBolillas();
        $bolillas[] = $numero;

        $this->setBolillas($bolillas);
        $this->bolilla_actual = $numero;
        $this->save();
    }

    public function registrarLinea(int $numeroBolilla): void
    {
        $this->bolilla_linea = $numeroBolilla;
        $this->tiempo_linea = Carbon::now()->diffInSeconds(Carbon::parse($this->inicio_sorteo));
        $this->save();
    }

    public function registrarBingo(int $numeroBolilla): void
    {
        $this->bolilla_bingo = $numeroBolilla;
        $this->tiempo_bingo = Carbon::now()->diffInSeconds(Carbon::parse($this->inicio_sorteo));
        $this->estado = 'finalizado';
        $this->fin_sorteo = Carbon::now();
        $this->save();
    }
}
