<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'edit_products',
            'delete_products',
            'delivery_operations',
            'stock_operations',
            'view_products',
            'purchase_products',
            'view_orders',
            'manage_users'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        $roles = [
            'admin' => $permissions, // Admin gets all permissions
            'employee' => [
                'edit_products',
                'view_products',
                'view_orders',
                'stock_operations'
            ],
            'delivery' => [
                'view_products',
                'view_orders',
                'delivery_operations'
            ],
            'customer' => [
                'view_products',
                'purchase_products'
            ]
        ];

        foreach ($roles as $role => $rolePermissions) {
            $role = Role::create(['name' => $role, 'guard_name' => 'web']);
            $role->givePermissionTo($rolePermissions);
        }
    }
} 