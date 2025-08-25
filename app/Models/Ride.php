<?php

namespace App\Models;
use App\Models\User;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;
   protected $fillable = [
    'user_id',
    'driver_id',
    'pickup_location',
    'dropoff_location',
    'phone_number',
    'driver_id',
    'status',
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


