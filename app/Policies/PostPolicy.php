<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('posts.view');
    }

    public function view(User $user, Post $model): bool
    {
        return $user->hasPermissionTo('posts.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('posts.create');
    }

    public function update(User $user, Post $model): bool
    {
        return $user->hasPermissionTo('posts.update');
    }

    public function delete(User $user, Post $model): bool
    {
        return $user->hasPermissionTo('posts.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('posts.delete');
    }
}
