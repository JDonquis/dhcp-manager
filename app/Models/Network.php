<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;

    protected $fillable = [
        'subnet',
        'netmask',
        'options',
        'params',
    ];

    protected $casts = [
        'subnet' => 'string',
        'netmask' => 'string',
        'options' => 'array',
        'params' => 'array',
    ];

}
