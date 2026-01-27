<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'bolillas_sacadas' => 'array',
        'inicio_sorteo'    => 'datetime',
        'fin_sorteo'       => 'datetime',
    ];

    // ================= RELACIONES =================

    public function jugada()
    {
        return $this->belongsTo(Jugada::class);
    }

    // ================= LÓGICA DE NEGOCIO =================

    public function iniciar()
    {
        $this->update([
            'estado' => 'en_curso',
            'inicio_sorteo' => now(),
            'bolillas_sacadas' => [],
        ]);
    }

    public function pausar()
    {
        $this->update(['estado' => 'pausado']);
    }

    public function reanudar()
    {
        $this->update(['estado' => 'en_curso']);
    }

    public function finalizar()
    {
        $this->update([
            'estado' => 'finalizado',
            'fin_sorteo' => now()
        ]);
    }

    public function agregarBolilla($numero)
    {
        $bolillas = $this->bolillas_sacadas ?? [];
        $bolillas[] = $numero;

        $this->update([
            'bolillas_sacadas' => $bolillas,
            'bolilla_actual' => $numero
        ]);
    }

    public function registrarLinea($numeroBolilla)
    {
        $this->update([
            'bolilla_linea' => $numeroBolilla,
            'tiempo_linea' => now()->diffInSeconds($this->inicio_sorteo)
        ]);
    }

    public function registrarBingo($numeroBolilla)
    {
        $this->update([
            'bolilla_bingo' => $numeroBolilla,
            'tiempo_bingo' => now()->diffInSeconds($this->inicio_sorteo),
            'estado' => 'finalizado',
            'fin_sorteo' => now()
        ]);
    }
}
