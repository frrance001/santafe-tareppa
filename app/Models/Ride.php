<?php

namespace App\Models;
use App\Models\User;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;
  
    protected $fillable = [
        'pickup_location',
        'dropoff_location',
        'status',
        'driver_id',
        'user_id',
        'phone_number',
        'pickup_lat',
        'pickup_lng',
        'dropoff_lat',
        'dropoff_lng',
        'rating',
        'feedback',
    ];


   // Optional relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function driver() {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function passenger() {
        return $this->belongsTo(User::class, 'user_id');
    }

}


