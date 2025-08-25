<?php

// app/Models/User.php
namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
protected $fillable = [
    'fullname',
    'email',
    'phone',
    'age',
    'sex',
    'password',
    'role',
];

}
