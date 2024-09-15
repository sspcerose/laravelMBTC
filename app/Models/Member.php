<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'tin',
        'mobile_num',
        'email',
        'password',
        'date_joined',
        'type',
        'member_status'
    ];

    public function driver()
    {
        return $this->hasMany(Driver::class, 'member_id');
    }

    public function owner()
    {
        return $this->hasMany(Owner::class, 'member_id');
    }

    public function vehicle()
    {
        return $this->hasMany(Vehicle::class, 'member_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'member_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


}
