<?php

namespace App\Services;

use App\Models\User;
use App\DataObjects\UserData;
use App\DataObjects\UserPasswordData;

class UserService
{
    public function create(UserData $user_data, UserPasswordData $user_password_data): User
    {
        $user = new User();
        $user->name = $user_data->name;
        $user->email = $user_data->email;
        $user->email_verified_at = $user_data->email_verified_at;
        $this->updatePassword($user, $user_password_data, false);
        $user->save();

        return $user;
    }

    public function update(User $user, UserData $user_data): User
    {
        $user->name = $user_data->name;
        $user->email = $user_data->email;
        if ($user_data->email_verified_at && ! $user->hasVerifiedEmail()) {
            $user->email_verified_at = $user_data->email_verified_at;
        }

        $user->save();

        return $user;
    }

    public function updatePassword(User $user, UserPasswordData $user_password_data, bool $save = true): User
    {
        if (! $user_password_data->password) {
            return $user;
        }

        $user->password = $user_password_data->password;
        $user->requires_password_update = $user_password_data->requires_password_update;

        if ($save) {
            $user->save();
        }

        if ($user_password_data->email_password) {
            $user->sendUpdatedPasswordNotification($user_password_data->password);
        }

        return $user;
    }
}
