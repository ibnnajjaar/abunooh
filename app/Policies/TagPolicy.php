<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view tags');
    }

    public function view(User $user, Tag $model): bool
    {
        return $user->hasPermissionTo('view tags');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create tags');
    }

    public function update(User $user, Tag $model): bool
    {
        return $user->hasPermissionTo('update tags');
    }

    public function delete(User $user, Tag $model): bool
    {
        return $user->hasPermissionTo('delete tags');
    }
}
