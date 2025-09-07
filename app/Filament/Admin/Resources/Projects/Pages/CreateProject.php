<?php

namespace App\Filament\Admin\Resources\Projects\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\Projects\ProjectResource;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
}
