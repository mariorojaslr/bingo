<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionCajero extends Model
{
    use HasFactory;

    protected $fillable = [
        'participante_id',
        'metodo_pago',
        'fichas',
        'monto_fiat',
        'estado',
        'comprobante_externo',
        'detalles_adicionales'
    ];

    public function participante()
    {
        return $this->belongsTo(PruebaParticipante::class, 'participante_id');
    }
}
