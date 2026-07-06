<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [

            // Products
            'product.view',
            'product.create',
            'product.update',
            'product.delete',

            // Categories
            'category.view',
            'category.create',
            'category.update',
            'category.delete',

            // Brands
            'brand.view',
            'brand.create',
            'brand.update',
            'brand.delete',

            // Orders
            'order.view',
            'order.create',
            'order.update',
            'order.delete',

            // Users
            'user.view',
            'user.create',
            'user.update',
            'user.delete',

            // Roles
            'role.view',
            'role.create',
            'role.update',
            'role.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
        'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
         'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'sanctum',
        ]);

        $warehouse = Role::firstOrCreate([
            'name' => 'warehouse',
           'guard_name' => 'web',
        ]);

        $support = Role::firstOrCreate([
            'name' => 'support',
            'guard_name' => 'sanctum',
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'sanctum',
        ]);

        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'product.view',
            'product.create',
            'product.update',

            'category.view',
            'category.create',
            'category.update',

            'brand.view',
            'brand.create',
            'brand.update',

            'order.view',
            'order.update',
        ]);

        $warehouse->givePermissionTo([
            'product.view',
            'product.update',
            'order.view',
        ]);

        $support->givePermissionTo([
            'order.view',
            'order.update',
            'user.view',
        ]);
    }
}