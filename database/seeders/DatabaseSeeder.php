<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        \App\Models\User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin123'), 
            'role' => 'admin', //[cite: 13, 31]
        ]);

        // Regular User
        \App\Models\User::factory()->create([
            'name' => 'Normal User',
            'email' => 'user@test.com',
            'password' => Hash::make('user123'), 
            'role' => 'user',// [cite: 13, 31]
        ]);
    }
}