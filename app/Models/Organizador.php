<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizador extends Model
{
    use HasFactory;

    protected $table = 'organizadores';

    protected $fillable = [
        'user_id',
        'tipo',
        'razon_social',
        'nombre_fantasia',
        'cuit',
        'email_contacto',
        'telefono',
        'direccion',
        'activo'
    ];

    public function jugadas()
    {
        return $this->hasMany(Jugada::class);
    }
}
