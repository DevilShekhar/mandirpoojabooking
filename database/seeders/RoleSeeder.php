<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Remove Finance Manager
        Role::where('name', 'Finance Manager')
            ->where('guard_name', 'web')
            ->delete();

        // Create roles if they don't exist
        $roles = [
            'Super Admin',
            'Admin',
            'Temple Manager',
            'Store Manager',
            'Pandit',
            'Darshan Verification Officer',
            'Delivery Manager',
            'Support Executive',
            'Customer',
            'Event Creator',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}