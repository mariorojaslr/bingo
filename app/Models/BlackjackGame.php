<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlackjackGame extends Model
{
    protected $fillable = [
        'empresa_id', 'participante_id', 'estado', 'bet_amount', 
        'deck', 'player_hand', 'dealer_hand', 'result'
    ];

    protected $casts = [
        'deck' => 'array',
        'player_hand' => 'array',
        'dealer_hand' => 'array',
    ];
}
