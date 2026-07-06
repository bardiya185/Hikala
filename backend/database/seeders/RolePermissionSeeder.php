<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

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

            // Permissions
            'permission.view',
            'permission.create',
            'permission.update',
            'permission.delete',

            // Categories
            'category.view',
            'category.create',
            'category.update',
            'category.delete',

            // Products
            'product.view',
            'product.create',
            'product.update',
            'product.delete',

            // Product Images
            'product-image.view',
            'product-image.create',
            'product-image.update',
            'product-image.delete',

            // Product Attributes
            'attribute.view',
            'attribute.create',
            'attribute.update',
            'attribute.delete',

            // Product Variants
            'variant.view',
            'variant.create',
            'variant.update',
            'variant.delete',

            // Inventory
            'inventory.view',
            'inventory.update',

            // Orders
            'order.view',
            'order.update',
            'order.cancel',

            // Cart
            'cart.view',
            'cart.update',

            // Coupons
            'coupon.view',
            'coupon.create',
            'coupon.update',
            'coupon.delete',

            // Comments
            'comment.view',
            'comment.approve',
            'comment.delete',

            // Brands
            'brand.view',
            'brand.create',
            'brand.update',
            'brand.delete',

            // Sliders
            'slider.view',
            'slider.create',
            'slider.update',
            'slider.delete',

            // Settings
            'setting.view',
            'setting.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'sanctum',
            ]);
        }

        // Roles
        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'sanctum',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'sanctum',
        ]);

        $warehouse = Role::firstOrCreate([
            'name' => 'warehouse',
            'guard_name' => 'sanctum',
        ]);

        $support = Role::firstOrCreate([
            'name' => 'support',
            'guard_name' => 'sanctum',
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'sanctum',
        ]);

        // Super Admin
        $superAdmin->syncPermissions(Permission::all());

        // Admin
        $admin->syncPermissions([
            'product.view',
            'product.create',
            'product.update',
            'product.delete',

            'category.view',
            'category.create',
            'category.update',
            'category.delete',

            'brand.view',
            'brand.create',
            'brand.update',
            'brand.delete',

            'attribute.view',
            'attribute.create',
            'attribute.update',
            'attribute.delete',

            'variant.view',
            'variant.create',
            'variant.update',
            'variant.delete',

            'product-image.view',
            'product-image.create',
            'product-image.update',
            'product-image.delete',

            'inventory.view',
            'inventory.update',

            'order.view',
            'order.update',
            'order.cancel',

            'comment.view',
            'comment.approve',

            'coupon.view',
            'coupon.create',
            'coupon.update',

            'slider.view',
            'slider.create',
            'slider.update',

            'setting.view',
            'setting.update',
        ]);

        // Warehouse
        $warehouse->syncPermissions([
            'product.view',
            'product.update',

            'inventory.view',
            'inventory.update',

            'order.view',
        ]);

        // Support
        $support->syncPermissions([
            'user.view',

            'order.view',
            'order.update',

            'comment.view',
            'comment.approve',
        ]);

        // Customer
        $customer->syncPermissions([]);
    }
}