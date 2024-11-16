<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => '',
            'email' => '',
            'password' => Hash::make(''),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
