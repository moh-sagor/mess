<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@messmeal.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'phone' => '+1234567890',
            'address' => '123 Admin Street, Admin City',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create Manager user
        $manager = User::create([
            'name' => 'Mess Manager',
            'email' => 'manager@messmeal.com',
            'email_verified_at' => now(),
            'password' => Hash::make('manager123'),
            'phone' => '+1234567891',
            'address' => '456 Manager Avenue, Manager City',
            'is_active' => true,
        ]);
        $manager->assignRole('manager');

        // Create sample regular users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@messmeal.com',
                'phone' => '+1234567892',
                'address' => '789 User Lane, User City',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@messmeal.com',
                'phone' => '+1234567893',
                'address' => '321 User Road, User City',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@messmeal.com',
                'phone' => '+1234567894',
                'address' => '654 User Boulevard, User City',
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice@messmeal.com',
                'phone' => '+1234567895',
                'address' => '987 User Street, User City',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('user123'),
                'phone' => $userData['phone'],
                'address' => $userData['address'],
                'is_active' => true,
            ]);
            $user->assignRole('user');
        }
    }
}
