<?php

namespace App\Models;

// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gcash_number',
        'reference_number',
        'amount',
        'screenshot',
        'status',
    ];
}
