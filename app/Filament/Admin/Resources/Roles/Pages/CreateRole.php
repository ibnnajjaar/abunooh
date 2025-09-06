<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\Roles\RoleResource;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;
}
