<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugada extends Model
{
    use HasFactory;

    protected $table = 'jugadas';

    protected $fillable = [
        'organizador_id',
        'institucion_id',
        'nombre_jugada',
        'serie',
        'numero_jugada',
        'fecha_evento',
        'hora_evento',
        'lugar',
        'formato_impresion',
        'cartones_por_hoja',
        'logo_path',
        'texto_encabezado',
        'texto_pie',
        'estado',
        'fecha_impresion',
        'cantidad_cartones',
        'cantidad_hojas',
        'precio_hoja',
        'total'
    ];

    public function organizador()
    {
        return $this->belongsTo(Organizador::class);
    }

    public function institucion()
    {
        return $this->belongsTo(Institucion::class);
    }

    public function cartones()
    {
        return $this->hasMany(JugadaCarton::class);
    }
}
