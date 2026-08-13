<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Una Tarifa pertenece a muchas Empresas
    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }
}
