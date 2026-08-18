<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MegaSorteo extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'draw_date',
        'status',
        'ticket_price',
        'accumulated_jackpot',
        'winning_numbers'
    ];

    protected $casts = [
        'draw_date' => 'datetime',
        'winning_numbers' => 'array',
    ];

    public function tickets()
    {
        return $this->hasMany(MegaSorteoTicket::class);
    }
}
