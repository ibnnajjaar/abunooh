<?php

namespace App\Filament\Admin\Resources\Tags\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\Tags\TagResource;

class EditTag extends EditRecord
{
    protected static string $resource = TagResource::class;
}
