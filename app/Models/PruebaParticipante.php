<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PruebaParticipante extends Model
{
    use HasFactory;

    protected $table = 'prueba_participantes';

    protected $fillable = [
        'token',
        'codigo_acceso',
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'email',
        'avatar',
        'activo',
        'jugada_id'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Cartones asignados a este participante piloto
     */
    public function cartones()
    {
        return $this->hasMany(
            \App\Models\ParticipanteCartonPrueba::class,
            'participante_prueba_id', // ESTE es el nombre real de la columna
            'id'
        );
    }

    /**
     * Jugada a la que pertenece
     */
    public function jugada()
    {
        return $this->belongsTo(
            \App\Models\Jugada::class,
            'jugada_id'
        );
    }
}
