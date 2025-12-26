<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Create admin user
        // Note: Password is plain text here, User model's setPasswordAttribute will hash it automatically
        User::create([
            'userid' => 'U001',
            'email' => 'admin@example.com',
            'password' => 'password123', // Will be auto-hashed by User model
            'firstname' => 'John',
            'lastname' => 'Doe',
            'groupid' => '1',
            'statuslogin' => 'A', // A = Active, I = Inactive
            'menusecurity' => 'all',
            'lastmoduser' => 'system',
            'lastmoddate' => now(),
            'email_verified_at' => now(),
        ]);

        // Create another test user
        User::create([
            'userid' => 'U002',
            'email' => 'user@example.com',
            'password' => 'password123', // Will be auto-hashed by User model
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'groupid' => '2',
            'statuslogin' => 'A', // A = Active, I = Inactive
            'menusecurity' => 'limited',
            'lastmoduser' => 'system',
            'lastmoddate' => now(),
            'email_verified_at' => now(),
        ]);

        User::create([
            'userid' => 'setya',
            'email' => 'setya@example.com',
            'password' => 'admin123', // Will be auto-hashed by User model
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'groupid' => '2',
            'statuslogin' => 'A', // A = Active, I = Inactive
            'menusecurity' => 'limited',
            'lastmoduser' => 'system',
            'lastmoddate' => now(),
            'email_verified_at' => now(),
        ]);
    }
}
