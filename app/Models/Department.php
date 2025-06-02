<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip_range_start',
        'ip_range_end'
    ];
    
    protected $casts = [
        'name' => 'string',
        'ip_range_start' => 'string',
        'ip_range_end' => 'string',

    ];


    public function groups(){

        return $this->hasMany(Group::class);
    }

    public function hosts(){
        return $this->hasMany(Host::class);
    }

    public function availableIps(){
        
        $start = ip2long($this->ip_range_start);
        $end = ip2long($this->ip_range_end);
        
        $assignedIps = $this->hosts->pluck('ip')->map('ip2long')->toArray();
        
        $available = [];
        for ($i = $start; $i <= $end; $i++) {
            if (!in_array($i, $assignedIps)) {
                $available[] = long2ip($i);
            }
        }
        
        return $available;
    }

}
