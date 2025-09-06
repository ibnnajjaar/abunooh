<?php

namespace App\Filament\Admin\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use App\Support\Enums\PostTypes;
use App\Support\Enums\PublishStatuses;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Details')
                    ->schema([
                        TextEntry::make('title')->inlineLabel(),
                        TextEntry::make('slug')->inlineLabel(),
                        TextEntry::make('excerpt')->inlineLabel(),
                        TextEntry::make('content')->inlineLabel(),
                        TextEntry::make('author.name')->inlineLabel()->label('Author'),
                        TextEntry::make('published_at')->inlineLabel()->dateTime(),
                        TextEntry::make('status')
                            ->inlineLabel()
                            ->badge()
                            ->color(fn ($state): string => PublishStatuses::getColor(is_string($state) ? $state : ($state?->value ?? '')))
                            ->formatStateUsing(fn ($state): string => is_string($state) ? str($state)->title() : (method_exists($state, 'value') ? str($state->value)->title() : '')),
                        TextEntry::make('post_type')
                            ->inlineLabel()
                            ->badge()
                            ->color(fn ($state): string => PostTypes::getColor(is_string($state) ? $state : ($state?->value ?? '')))
                            ->formatStateUsing(fn ($state): string => is_string($state) ? str($state)->title() : (method_exists($state, 'value') ? str($state->value)->title() : '')),
                        TextEntry::make('is_menu_item')->inlineLabel()->boolean(),
                        TextEntry::make('og_image_url')->inlineLabel()->label('OG Image URL'),
                        TextEntry::make('created_at')->inlineLabel()->dateTime(),
                        TextEntry::make('updated_at')->inlineLabel()->dateTime(),
                    ]),
            ])->columns(1);
    }
}
