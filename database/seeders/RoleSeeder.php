<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rename existing role instead of creating a new one
        Role::where('name', 'Puja Manager')
            ->where('guard_name', 'web')
            ->update(['name' => 'Store Manager']);

        // Create other roles only if they don't already exist
        $roles = [
            'Super Admin',
            'Admin',
            'Temple Manager',
            'Pandit',
            'Finance Manager',
            'Delivery Manager',
            'Support Executive',
            'Customer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
    }
}