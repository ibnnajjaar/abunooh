<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use App\Models\TimeEntry;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TimeEntryPolicy
{

    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Employee) {
            return true;
        }

        /* @var User $user */
        return $user->hasPermissionTo('view time entries')
            || $user->hasPermissionTo('view any time entry');
    }

    public function view(Authenticatable $user, TimeEntry $time_entry): bool
    {
        if ($user instanceof Employee) {
            return $time_entry->employee_id == $user->id;
        }

        /* @var User $user */
        if ($user->hasPermissionTo('view any time entry')) {
            return true;
        }

        return $user->hasPermissionTo('view time entries')
            && $user->isSupervisorOf($time_entry->employee);
    }

    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Employee) {
            return false;
        }

        /* @var User $user */
        return $user->hasPermissionTo('create time entries');
    }

    public function update(Authenticatable $user, TimeEntry $time_entry): bool
    {
        if ($user instanceof Employee) {
            return false;
        }

        /* @var User $user */
        if ($user->hasPermissionTo('update any time entry')) {
            return true;
        }

        return $user->hasPermissionTo('update time entries')
            && $user->isSupervisorOf($time_entry->employee);
    }

    public function delete(Authenticatable $user, TimeEntry $time_entry): bool
    {
        if ($user instanceof Employee) {
            return false;
        }

        /* @var User $user */
        if ($user->hasPermissionTo('delete any time entry')) {
            return true;
        }

        return $user->hasPermissionTo('delete time entries')
            && $user->isSupervisorOf($time_entry->employee);
    }

    public function deleteAny(Authenticatable $user): bool
    {
        if ($user instanceof Employee) {
            return false;
        }

        /* @var User $user */
        return $user->hasPermissionTo('delete any time entry');
    }


}
