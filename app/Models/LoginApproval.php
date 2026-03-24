<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginApproval extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'status',
        'expires_at',
        'ip_address'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
