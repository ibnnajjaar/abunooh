<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\DataObjects\UserData;
use App\Services\UserService;
use App\DataObjects\UserPasswordData;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\Users\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return (new UserService())->create(
            UserData::fromArray($data),
            UserPasswordData::fromArray($data),
        );
    }
}
