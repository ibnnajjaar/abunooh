<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('role.view');
    }

    public function view(User $user, Role $model): bool
    {
        return $user->hasPermissionTo('role.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('role.create');
    }

    public function update(User $user, Role $model): bool
    {
        return $user->hasPermissionTo('role.update');
    }

    public function delete(User $user, Role $model): bool
    {
        return $user->hasPermissionTo('role.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('role.delete');
    }
}
