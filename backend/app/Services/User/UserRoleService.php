<?php

namespace App\Services\User;

use App\Models\Role;
use App\Models\User;

class UserRoleService
{
    public function getRoles(User $user): array
    {
        return [
            'roles' => $user->getRoleNames()->values()->toArray(),
            'permissions' => $user->getAllPermissions()->pluck('name')->values()->toArray(),
        ];
    }

    public function syncPrimaryRole(User $targetUser, string $roleName, User $actor): User
    {
        $role = $this->findRole($roleName);

        $this->ensureCanAssign($role, $actor);

        $targetUser->syncRoles([$role->name]);

        return $targetUser->fresh();
    }

    public function attachRole(User $targetUser, string $roleName, User $actor): User
    {
        $role = $this->findRole($roleName);

        $this->ensureCanAssign($role, $actor);

        if (!$targetUser->hasRole($role->name)) {
            $targetUser->assignRole($role->name);
        }


        return $targetUser->fresh();
    }

    public function removeRole(User $targetUser, Role $role, User $actor): User
    {
        $this->ensureCanAssign($role, $actor);
        if ($role->name === 'super-admin') {
            $superAdminCount = User::role('super-admin')->count();

            if ($superAdminCount <= 1 && $targetUser->hasRole('super-admin')) {
                abort(400, 'Cannot remove the last super-admin.');
            }
        }

        if ($targetUser->hasRole($role->name)) {
            $targetUser->removeRole($role->name);
        }

        return $targetUser->fresh();
    }

    private function findRole(string $roleName): Role
    {
        $role = Role::query()
            ->where('name', $roleName)
            ->where('guard_name', 'sanctum')
            ->first();

        if (!$role) {
            abort(422, "Role '{$roleName}' not found.");
        }

        return $role;
    }

    private function ensureCanAssign(Role $role, User $actor): void
    {
        if ($role->name === 'super-admin' && !$actor->hasRole('super-admin')) {
            abort(403, 'Only super-admin can manage super-admin role.');
        }
    }
}