<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'dues_id',
        'last_payment',
        'status'
    ];

    public function member() 
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function dues()
    {
        return $this->belongsTo(Dues::class, 'dues_id');
    }



}

