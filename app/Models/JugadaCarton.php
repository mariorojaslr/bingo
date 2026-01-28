<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JugadaCarton extends Model
{
    use HasFactory;

    protected $table = 'jugada_carton';

    protected $fillable = [
        'jugada_id',
        'carton_id',
        'lote_impresion',
        'numero_hoja',
        'posicion_en_hoja',
        'orden',
    ];

    // Relación con el cartón físico
    public function carton()
    {
        return $this->belongsTo(Carton::class, 'carton_id');
    }

    // Relación con la jugada
    public function jugada()
    {
        return $this->belongsTo(Jugada::class, 'jugada_id');
    }
}
