<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carton extends Model
{
    use HasFactory;

    protected $table = 'cartons';

    protected $fillable = [
        'serie',
        'numero_carton',
        'formato',
        'grilla',
        'estado',
    ];

    protected $casts = [
        'grilla' => 'array',
    ];

    /**
     * Devuelve la grilla con los números ordenados de menor a mayor 
     * verticalmente por cada columna, respetando los huecos vacíos.
     */
    public function getGrillaOrdenadaAttribute(): array
    {
        $grilla = $this->grilla;
        if (!is_array($grilla) || count($grilla) !== 3) {
            return $grilla;
        }

        for ($col = 0; $col < 9; $col++) {
            $numeros = [];
            for ($row = 0; $row < 3; $row++) {
                if (isset($grilla[$row][$col]) && $grilla[$row][$col] > 0) {
                    $numeros[] = $grilla[$row][$col];
                }
            }
            sort($numeros);
            
            $i = 0;
            for ($row = 0; $row < 3; $row++) {
                if (isset($grilla[$row][$col]) && $grilla[$row][$col] > 0) {
                    $grilla[$row][$col] = $numeros[$i];
                    $i++;
                }
            }
        }

        return $grilla;
    }

    /* =======================
     |  RELACIONES
     ======================= */

    public function jugadas()
    {
        return $this->belongsToMany(Jugada::class, 'jugada_carton')
            ->withPivot(
                'lote_impresion_id',
                'numero_hoja',
                'posicion_en_hoja',
                'estado'
            )
            ->withTimestamps();
    }

    public function jugadaCartones()
    {
        return $this->hasMany(JugadaCarton::class);
    }

    /* =======================
     |  LÓGICA DE JUEGO
     ======================= */

    /**
     * ¿Este cartón tiene BINGO?
     */
    public function esBingo(array $bolillas): bool
    {
        foreach ($this->grilla as $fila) {
            foreach ($fila as $numero) {
                if ($numero !== 0 && !in_array($numero, $bolillas)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * ¿Este cartón tiene al menos una LÍNEA?
     */
    public function tieneLinea(array $bolillas): bool
    {
        // filas
        foreach ($this->grilla as $fila) {
            if ($this->lineaCompleta($fila, $bolillas)) {
                return true;
            }
        }

        // columnas
        for ($c = 0; $c < 9; $c++) {
            $columna = [];
            foreach ($this->grilla as $fila) {
                $columna[] = $fila[$c];
            }
            if ($this->lineaCompleta($columna, $bolillas)) {
                return true;
            }
        }

        return false;
    }

    private function lineaCompleta(array $linea, array $bolillas): bool
    {
        foreach ($linea as $numero) {
            if ($numero !== 0 && !in_array($numero, $bolillas)) {
                return false;
            }
        }
        return true;
    }
}
