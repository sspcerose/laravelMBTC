<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
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
}
