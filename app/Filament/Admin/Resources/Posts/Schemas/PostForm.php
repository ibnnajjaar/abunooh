<?php

namespace App\Filament\Admin\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use App\Support\Enums\PostTypes;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Support\Enums\PublishStatuses;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::render())
            ->columns(1);
    }

    public static function render(): array
    {
        return [
            Section::make('Post Details')
                ->schema([
                    TextInput::make('title')
                        ->inlineLabel()
                        ->required(),
                    TextInput::make('slug')
                        ->inlineLabel()
                        ->unique(ignoreRecord: true),
                    Textarea::make('excerpt')
                        ->inlineLabel()
                        ->rows(3),
                    Textarea::make('content')
                        ->inlineLabel()
                        ->rows(10),
                    Select::make('author_id')
                        ->inlineLabel()
                        ->label('Author')
                        ->relationship('author', 'name')
                        ->searchable()
                        ->preload(),
                    DateTimePicker::make('published_at')
                        ->inlineLabel(),
                    Select::make('status')
                        ->inlineLabel()
                        ->options(PublishStatuses::options())
                        ->required(),
                    Select::make('post_type')
                        ->inlineLabel()
                        ->options(PostTypes::options())
                        ->required(),
                    Toggle::make('is_menu_item')
                        ->inlineLabel(),
                    TextInput::make('og_image_url')
                        ->inlineLabel()
                        ->label('OG Image URL')
                        ->url(),
                ])
                ->columnSpan(1),
        ];
    }
}
