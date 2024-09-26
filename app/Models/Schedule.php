<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'driver_id',
        'vehicle_id',
        'cust_status',
        'driver_status'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'book_id');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id'); 
    }
    

   
}
