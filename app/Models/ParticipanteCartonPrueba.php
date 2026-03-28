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

    public function participante()
    {
        return $this->belongsTo(
            \App\Models\PruebaParticipante::class,
            'participante_prueba_id'
        );
    }

    public function carton()
    {
        return $this->belongsTo(
            \App\Models\Carton::class,
            'carton_id'
        );
    }
}
