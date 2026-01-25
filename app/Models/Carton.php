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
        'numero_carton',   // número mágico único
        'formato',
        'grilla',
        'estado',
    ];

    protected $casts = [
        'grilla' => 'array',
    ];

    /**
     * Relación con Jugadas a través de la tabla pivote jugada_carton
     */
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

    /**
     * Relación directa con los registros de la tabla pivote
     */
    public function jugadaCartones()
    {
        return $this->hasMany(JugadaCarton::class);
    }
}
