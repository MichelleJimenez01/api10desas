<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    public function run()
    {
        // Usuario administrador
        User::create([
            'firstname' => 'luis',
            'lastname' => 'tao',
            'email' => 'admin@example.com',
            'location' => 'Central Office',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        // Usuario regular
        User::create([
            'firstname' => 'milena',
            'lastname' => 'moreno',
            'email' => 'user@example.com',
            'location' => 'Branch Office',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
    }
}