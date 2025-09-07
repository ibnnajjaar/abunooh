<?php

namespace App\Filament\Admin\Resources\Tags\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\Tags\TagResource;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;
}
