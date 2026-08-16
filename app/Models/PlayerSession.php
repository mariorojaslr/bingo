<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayerSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'participante_id',
        'session_start',
        'session_end',
        'duration_minutes',
    ];

    protected $casts = [
        'session_start' => 'datetime',
        'session_end' => 'datetime',
    ];

    public function participante()
    {
        return $this->belongsTo(PruebaParticipante::class, 'participante_id');
    }
}
