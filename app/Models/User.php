<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'age',
        'sex',
        'role',
        'status',
        'business_permit',
        'barangay_clearance',
        'photo',
        'police_clearance',
        'password',
        'availability',
        'is_verified',
        'is_available',
        'verification_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🚖 Complaints where this user is the passenger
    public function complaintsAsPassenger()
    {
        return $this->hasMany(Complaint::class, 'passenger_id');
    }

    // 🚗 Complaints where this user is the driver
    public function complaintsAsDriver()
    {
        return $this->hasMany(Complaint::class, 'driver_id');
    }

    // ⭐ Ratings received (can be passenger or driver)
    public function ratingsReceived()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    // 📝 Ratings this user has given to others
    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    // ⚡ Helper to get average rating (float)
    public function averageRating(): float
    {
        return round($this->ratingsReceived()->avg('score') ?? 0, 2);
    }
}
