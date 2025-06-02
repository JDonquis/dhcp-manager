<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_id'
    ];
    
    protected $casts = [
        'name' => 'string',

    ];
    
    public function department(){

        return $this->belongsTo(Department::class);
    }

    public function hosts(){
        return $this->hasMany(Host::class);
    }
}
