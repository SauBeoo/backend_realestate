<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        Admin::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@realestate.com',
            'password' => Hash::make('SuperAdmin123!'),
            'phone' => '0901234567',
            'role' => 'super_admin',
            'status' => 'active',
            'email_verified_at' => now(),
            'bio' => 'System Super Administrator with full access to all features.',
            'receive_notifications' => true,
            'receive_system_alerts' => true,
        ]);

        // Create Regular Admin
        Admin::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'admin',
            'email' => 'admin@realestate.com',
            'password' => Hash::make('Admin123!'),
            'phone' => '0912345678',
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
            'bio' => 'Regular administrator for daily operations.',
            'receive_notifications' => true,
            'receive_system_alerts' => true,
            'created_by' => 1, // Created by super admin
        ]);

        // Create Moderator
        Admin::create([
            'first_name' => 'Moderator',
            'last_name' => 'User',
            'username' => 'moderator',
            'email' => 'moderator@realestate.com',
            'password' => Hash::make('Moderator123!'),
            'phone' => '0923456789',
            'role' => 'moderator',
            'status' => 'active',
            'email_verified_at' => now(),
            'bio' => 'Content moderator with limited administrative rights.',
            'permissions' => ['view_properties', 'edit_properties', 'view_users'],
            'receive_notifications' => true,
            'receive_system_alerts' => false,
            'created_by' => 1, // Created by super admin
        ]);

        // Create Test Admin (Inactive)
        Admin::create([
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'username' => 'testadmin',
            'email' => 'test@realestate.com',
            'password' => Hash::make('TestAdmin123!'),
            'phone' => '0934567890',
            'role' => 'admin',
            'status' => 'inactive',
            'email_verified_at' => now(),
            'bio' => 'Test admin account for development purposes.',
            'receive_notifications' => false,
            'receive_system_alerts' => false,
            'created_by' => 1, // Created by super admin
        ]);

        $this->command->info('Admin users seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Super Admin: superadmin@realestate.com / SuperAdmin123!');
        $this->command->info('Admin: admin@realestate.com / Admin123!');
        $this->command->info('Moderator: moderator@realestate.com / Moderator123!');
    }
} 