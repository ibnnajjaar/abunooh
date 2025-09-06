<?php

namespace App\Filament\Admin\Resources\Posts\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\Posts\PostResource;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
