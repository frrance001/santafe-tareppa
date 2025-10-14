<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['ride_id','method','amount','status','gateway_id'];
    
    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
