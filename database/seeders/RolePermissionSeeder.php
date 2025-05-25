<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Meal management
            'view meals',
            'create meals',
            'edit meals',
            'delete meals',
            'submit meal preferences',
            'view meal preferences',
            'edit meal preferences',
            
            // Meal category management
            'view meal categories',
            'create meal categories',
            'edit meal categories',
            'delete meal categories',
            
            // Bazaar management
            'view bazaar records',
            'create bazaar records',
            'edit bazaar records',
            'delete bazaar records',
            
            // Expense management
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
            'approve expenses',
            'reject expenses',
            
            // Cost sharing
            'view cost sharing',
            'calculate cost sharing',
            'edit cost sharing',
            
            // Reports
            'view reports',
            'export reports',
            
            // System settings
            'manage system settings',
            
            // Profile management
            'view own profile',
            'edit own profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role - full access
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Manager role - can manage meals, bazaar, expenses but not users
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
            'view meals',
            'create meals',
            'edit meals',
            'delete meals',
            'view meal preferences',
            'edit meal preferences',
            'view meal categories',
            'view bazaar records',
            'create bazaar records',
            'edit bazaar records',
            'delete bazaar records',
            'view expenses',
            'create expenses',
            'edit expenses',
            'view cost sharing',
            'view reports',
            'view own profile',
            'edit own profile',
        ]);

        // User role - basic access
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'submit meal preferences',
            'view meals',
            'view meal categories',
            'view bazaar records',
            'view expenses',
            'view cost sharing',
            'view own profile',
            'edit own profile',
        ]);
    }
}
