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
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'provincia',
        'pais',
        'texto_encabezado',
        'texto_pie',
        'logo',
        'activo',
        'user_id'
    ];


    protected $casts = [
        'activo' => 'boolean',
    ];
}
