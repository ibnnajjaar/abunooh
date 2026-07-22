<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('user.view');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('user.create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.update');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.delete');
    }

    public function deleteAny(User $user): User|bool
    {
        return $user->hasPermissionTo('user.delete.any');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.force_delete');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.force_delete');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermissionTo('user.force_delete.any');
    }
}
