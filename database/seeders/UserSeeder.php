<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'uuid' => Str::uuid(),
            'name' => 'Admin User',
            'email' => 'admin@viomia.com',
            'phone_number' => '+250788111111',
            'country_code' => '+250',
            'password' => Hash::make('admin123'),
            'role_id' => 1, // Admin
            'otp' => '123456',
            'is_active' => true,
            'is_default_pin' => true,
        ]);

        // Support User
        User::create([
            'uuid' => Str::uuid(),
            'name' => 'Support User',
            'email' => 'support@viomia.com',
            'phone_number' => '+250788222222',
            'country_code' => '+250',
            'password' => Hash::make('support123'),
            'role_id' => 2, // Support
            'otp' => '234567',
            'is_active' => true,
            'is_default_pin' => true,
        ]);

        // Regular User 1
        User::create([
            'uuid' => Str::uuid(),
            'name' => 'John Trader',
            'email' => 'john@example.com',
            'phone_number' => '+250788333333',
            'country_code' => '+250',
            'password' => Hash::make('john@trader123'),
            'role_id' => 3, // Regular user
            'otp' => '345678',
            'profile_photo' => null,
            'is_active' => true,
            'is_default_pin' => true,
        ]);

        // Regular User 2
        User::create([
            'uuid' => Str::uuid(),
            'name' => 'Jane Investor',
            'email' => 'jane@example.com',
            'phone_number' => '+250788444444',
            'country_code' => '+250',
            'password' => Hash::make('jane@invest123'),
            'role_id' => 3, // Regular user
            'otp' => '456789',
            'profile_photo' => null,
            'is_active' => true,
            'is_default_pin' => true,
        ]);

        // Regular User 3
        User::create([
            'uuid' => Str::uuid(),
            'name' => 'Bob Manager',
            'email' => 'bob@example.com',
            'phone_number' => '+250788555555',
            'country_code' => '+250',
            'password' => Hash::make('bob@manager123'),
            'role_id' => 3, // Regular user
            'otp' => '567890',
            'profile_photo' => null,
            'is_active' => true,
            'is_default_pin' => true,
        ]);
    }
}
