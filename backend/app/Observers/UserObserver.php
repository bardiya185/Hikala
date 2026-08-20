<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        if (!$user->hasAnyRole()) {
            $defaultRole = Role::where('name', 'user')
                ->where('guard_name', 'sanctum')
                ->first();

            if ($defaultRole) {
                $user->assignRole($defaultRole);
            }
        }
    }
}