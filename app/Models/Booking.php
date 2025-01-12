<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;


    protected $fillable = [
        'customer_id',
        'tariff_id',
        'passenger',
        'location',
        'time',
        'destination',
        'receipt',
        'start_date',
        'end_date',
        'price',
        'remaining',
        'status'
    ];
    


    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class, 'tariff_id');
    }

    public function schedule() 
    {
        return $this->hasMany(Schedule::class, 'book_id');
    }
    public function driver() 
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    

}
