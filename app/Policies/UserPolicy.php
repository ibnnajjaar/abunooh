<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view users');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('view users');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create users');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('update users');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('delete users');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete any user');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasPermissionTo('force delete users');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('force delete users');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->hasPermissionTo('force delete any user');
    }
}
