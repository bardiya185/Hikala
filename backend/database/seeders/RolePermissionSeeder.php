<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Creating roles and permissions...');

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-products',
            'create-products',
            'update-products',
            'delete-products',
            'view-categories',
            'create-categories',
            'update-categories',
            'delete-categories',
            'view-brands',
            'create-brands',
            'update-brands',
            'delete-brands',
            'view-orders',
            'create-orders',
            'update-orders',
            'cancel-orders',
            'refund-orders',
            'view-users',
            'create-users',
            'update-users',
            'delete-users',
            'assign-roles',
            'view-reviews',
            'create-reviews',
            'update-reviews',
            'delete-reviews',
            'approve-reviews',
            'reject-reviews',
            'view-discounts',
            'create-discounts',
            'update-discounts',
            'delete-discounts',
            'view-campaigns',
            'create-campaigns',
            'update-campaigns',
            'delete-campaigns',
            'view-coupons',
            'create-coupons',
            'update-coupons',
            'delete-coupons',
            'view-banners',
            'create-banners',
            'update-banners',
            'delete-banners',
            'view-settings',
            'update-settings',
            'view-reports',
            'export-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'sanctum',
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'sanctum',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'sanctum',
        ]);

        $seller = Role::firstOrCreate([
            'name' => 'seller',
            'guard_name' => 'sanctum',
        ]);

        $user = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'sanctum',
        ]);

        $superAdmin->syncPermissions($permissions);

        $admin->syncPermissions([
            'view-products',
            'create-products',
            'update-products',
            'delete-products',

            'view-categories',
            'create-categories',
            'update-categories',
            'delete-categories',

            'view-brands',
            'create-brands',
            'update-brands',
            'delete-brands',

            'view-orders',
            'update-orders',
            'cancel-orders',
            'refund-orders',

            'view-users',
            'update-users',

            'view-reviews',
            'approve-reviews',
            'reject-reviews',
            'delete-reviews',

            'view-discounts',
            'create-discounts',
            'update-discounts',
            'delete-discounts',

            'view-campaigns',
            'create-campaigns',
            'update-campaigns',
            'delete-campaigns',

            'view-coupons',
            'create-coupons',
            'update-coupons',
            'delete-coupons',

            'view-banners',
            'create-banners',
            'update-banners',
            'delete-banners',

            'view-reports',
        ]);

        $seller->syncPermissions([
            'view-products',
            'create-products',
            'update-products',
            'view-orders',
            'view-reviews',
            'view-discounts',
            'view-reports',
        ]);

        $user->syncPermissions([
            'view-products',
            'view-categories',
            'view-brands',
            'create-orders',
            'view-orders',
            'cancel-orders',
            'create-reviews',
            'update-reviews',
            'delete-reviews',
        ]);

        $allUsers = User::all();

        if ($allUsers->isNotEmpty()) {
            $firstUser = $allUsers->first();
            if ($firstUser && !$firstUser->hasRole('super-admin')) {
                $firstUser->assignRole('super-admin');
            }

            $secondUser = $allUsers->skip(1)->first();
            if ($secondUser && !$secondUser->hasRole('admin')) {
                $secondUser->assignRole('admin');
            }

            $thirdUser = $allUsers->skip(2)->first();
            if ($thirdUser && !$thirdUser->hasRole('seller')) {
                $thirdUser->assignRole('seller');
            }

            foreach ($allUsers->skip(3) as $u) {
                if (!$u->hasRole('user')) {
                    $u->assignRole('user');
                }
            }
        }

        $this->command->info('✅ Roles and permissions created successfully.');
        $this->command->line('   • Permissions: ' . Permission::count());
        $this->command->line('   • Roles: ' . Role::count());
    }
}