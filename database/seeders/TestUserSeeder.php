<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a main test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'passcode' => Hash::make('123456'),
        ]);

        // Create a user without passcode
        User::create([
            'name' => 'New User',
            'email' => 'new@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'passcode' => null,
        ]);

        // Create some additional test users
        User::factory(3)->create();
    }
}