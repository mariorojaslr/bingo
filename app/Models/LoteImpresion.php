<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoteImpresion extends Model
{
    protected $table = 'lotes_impresion';

    protected $fillable = [
        'jugada_id',
        'codigo_lote',
        'cantidad_cartones',
        'cantidad_hojas',
        'precio_hoja',
        'total_impresion',
        'costo_generacion',
        'total_general',
        'estado'
    ];

    /**
     * Cast automático de fechas a Carbon
     * para poder usar ->format() en Blade
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Jugada
     */
    public function jugada()
    {
        return $this->belongsTo(Jugada::class);
    }
}
