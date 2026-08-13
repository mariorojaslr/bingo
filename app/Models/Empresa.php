<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Una Empresa tiene muchas Jugadas (Salas)
    public function jugadas()
    {
        return $this->hasMany(Jugada::class);
    }
}
