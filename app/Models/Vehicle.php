<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;


    protected $fillable = [
        'member_id',
        'type',
        'plate_num',
        'capacity',
        'status'

    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    public function schedule() 
    {
        return $this->hasMany(Schedule::class, 'vehicle_id'); 
    }
    public function owner() {
        return $this->belongsTo(Owner::class, 'owner_id');
    }
}
