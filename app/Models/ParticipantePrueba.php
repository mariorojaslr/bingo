<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantePrueba extends Model
{
    protected $table = 'participantes_prueba';

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'email',
        'avatar',
    ];

    public function cartones()
    {
        return $this->belongsToMany(Carton::class, 'participante_carton_prueba')
            ->withPivot('jugada_id')
            ->withTimestamps();
    }
}
