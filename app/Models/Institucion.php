<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    use HasFactory;

    protected $table = 'instituciones';

    protected $fillable = [
        'nombre',
        'tipo',
        'logo',
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'provincia',
        'pais',
        'texto_encabezado',
        'texto_pie',
        'activa'
    ];

    public function jugadas()
    {
        return $this->hasMany(Jugada::class);
    }
}
