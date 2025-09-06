<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasPermissionTo('view any employee')) {
            return true;
        }

        return $user->hasPermissionTo('view employees');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Employee $employee): bool
    {
        if ($user->hasPermissionTo('view any employee')) {
            return true;
        }

        return $user->hasPermissionTo('view employees')
            && $employee->supervisor_id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create employees');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Employee $employee): bool
    {
        if ($user->hasPermissionTo('update any employee')) {
            return true;
        }

        return $user->hasPermissionTo('update employees')
            && $employee->supervisor_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Employee $employee): bool
    {
         if ($user->hasPermissionTo('delete any employee')) {
             return true;
         }

         return $user->hasPermissionTo('delete employees')
             && $employee->supervisor_id == $user->id;
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete any employee');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Employee $employee): bool
    {
        return $user->can('forceDelete', $employee);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Employee $employee): bool
    {
        if ($user->hasPermissionTo('force delete any employee')) {
            return true;
        }

        return $user->hasPermissionTo('force delete employees')
            && $employee->supervisor_id == $user->id;
    }
}
