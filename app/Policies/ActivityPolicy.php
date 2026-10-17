<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Activity;

class ActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('activity.view');
    }

    public function view(User $user, Activity $model): bool
    {
        return $user->hasPermissionTo('activity.view');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Activity $model): bool
    {
        return false;
    }

    public function delete(User $user, Activity $model): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
