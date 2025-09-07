<?php

namespace App\Filament\Admin\Resources\Tags\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\Tags\TagResource;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;
}
