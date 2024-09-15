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
}
