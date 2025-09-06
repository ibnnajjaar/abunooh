<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Models\User;
use App\DataObjects\UserData;
use App\Services\UserService;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\DataObjects\UserPasswordData;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\Users\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /* @var User $record */
        $user_data = UserData::fromArray($data);
        $password_data = UserPasswordData::fromArray($data);

        (new UserService())->update($record, $user_data);
        (new UserService())->updatePassword($record, $password_data);

        return $record->refresh();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
