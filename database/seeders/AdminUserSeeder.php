<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'fullname' => 'Im Admin',
            'email' => 'admin@gmail.com',
            'phone' => '09152626420',
            'age' => 30,
            'sex' => 'Unknown',
            'role' => 'Admin',
            'password' => Hash::make('thissisadmin00'), // choose a secure password
        ]);
    }
}
