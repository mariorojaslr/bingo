<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlackjackTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'name',
        'status',
        'deck',
        'dealer_hand',
        'current_turn_seat',
        'action_deadline'
    ];

    protected $casts = [
        'deck' => 'array',
        'dealer_hand' => 'array',
        'action_deadline' => 'datetime',
    ];

    public function seats()
    {
        return $this->hasMany(BlackjackSeat::class);
    }
}
