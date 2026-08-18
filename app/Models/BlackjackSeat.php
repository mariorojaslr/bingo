<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlackjackSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'blackjack_table_id',
        'seat_number',
        'participante_id',
        'status',
        'bet_amount',
        'hand',
        'result',
        'payout'
    ];

    protected $casts = [
        'hand' => 'array',
    ];

    public function table()
    {
        return $this->belongsTo(BlackjackTable::class, 'blackjack_table_id');
    }

    public function participante()
    {
        return $this->belongsTo(PruebaParticipante::class, 'participante_id');
    }
}
