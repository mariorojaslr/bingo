<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipanteCartonPrueba extends Model
{
    protected $table = 'participante_carton_prueba';

    protected $fillable = [
        'participante_prueba_id',
        'jugada_id',
        'carton_id',
    ];
}
