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

    // Relación con el participante
    public function participante()
    {
        return $this->belongsTo(PruebaParticipante::class, 'participante_prueba_id');
    }

    // Relación con la jugada
    public function jugada()
    {
        return $this->belongsTo(Jugada::class, 'jugada_id');
    }

    // Relación con el cartón 3x9
    public function carton()
    {
        return $this->belongsTo(Carton::class, 'carton_id');
    }
}
