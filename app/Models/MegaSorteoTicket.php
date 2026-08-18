<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MegaSorteoTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'mega_sorteo_id',
        'participante_id',
        'numbers',
        'hits',
        'won_amount'
    ];

    protected $casts = [
        'numbers' => 'array',
    ];

    public function megaSorteo()
    {
        return $this->belongsTo(MegaSorteo::class);
    }

    public function participante()
    {
        return $this->belongsTo(PruebaParticipante::class, 'participante_id');
    }
}
