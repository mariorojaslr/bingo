<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PruebaParticipante extends Model
{
    protected $table = 'prueba_participantes';

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'email',
        'avatar',
        'token',
        'codigo_acceso',
    ];
}
