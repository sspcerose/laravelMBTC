<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dues extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'date'

    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'dues_id');
    }

    
}
