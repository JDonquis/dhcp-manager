<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user',
        'department_id',
        'group_id',
        'mac',
        'ip',
    ];

    protected $casts = [
        'name' => 'string',
        'user' => 'string',
        'mac' => 'string',
        'ip' => 'string',
    ];

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }
}
