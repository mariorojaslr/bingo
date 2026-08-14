<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SorteoGanador extends Model
{
    use HasFactory;

    protected $fillable = [
        'sorteo_id',
        'jugada_id',
        'tipo_premio',
        'carton_numero',
        'nombre_jugador',
        'bolilla_ganadora'
    ];
}
