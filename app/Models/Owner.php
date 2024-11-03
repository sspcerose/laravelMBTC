<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'member_type'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function vehicle() {
        return $this->hasMany(Vehicle::class, 'onwer_id'); 
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'owner_id');
    }
}
