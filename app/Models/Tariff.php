<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination',
        'rate',
        'succeeding',
        'status'
    ];

    public function booking()
    {
        return $this->hasMany(Booking::class, 'tariff_id');
    }
}
