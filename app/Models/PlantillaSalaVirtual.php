<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaSalaVirtual extends Model
{
    use HasFactory;

    protected $table = 'plantillas_salas_virtuales';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'intervalo_minutos',
        'precio_carton',
        'duracion_minutos',
        'porcentaje_pozo',
        'limite_bolilla_pozo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio_carton' => 'decimal:2',
        'porcentaje_pozo' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
