<?php

namespace App\Filament\Admin\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use App\Support\Enums\PostTypes;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Grid;
use App\Support\Enums\PublishStatuses;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Set;

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
            Grid::make()
                ->columns(3)
                ->columnSpanFull()
                ->schema([
                    Grid::make()
                        ->columnSpan(2)
                        ->schema([
                            Section::make()
                                   ->columnSpanFull()
                                   ->schema([
                                       TextInput::make('title')
                                                ->required(),
                                       TextInput::make('slug')
                                                ->unique(ignoreRecord: true),
                                   ]),
                            Section::make()
                                   ->columnSpanFull()
                                   ->schema([
                                       MarkdownEditor::make('excerpt'),
                                       MarkdownEditor::make('content'),
                                   ]),
                        ]),
                    Grid::make()
                        ->columnSpan(1)
                        ->schema([
                            Section::make('Meta')
                                   ->columnSpanFull()
                                   ->schema([
                                       FileUpload::make('og_image_url')
                                                 ->label('OG Image')
                                                 ->image(),
                                       Select::make('author_id')
                                             ->label('Author')
                                             ->relationship('author', 'name')
                                             ->searchable()
                                             ->preload(),
                                       DateTimePicker::make('published_at'),
                                       Select::make('status')
                                             ->searchable()
                                             ->options(PublishStatuses::options())
                                             ->required(),
                                       Select::make('post_type')
                                             ->searchable()
                                             ->options(PostTypes::options())
                                             ->required(),
                                       Checkbox::make('is_menu_item')
                                               ->label('Show this post in the menu'),

                                   ]),
                        ]),
                ]),
        ];
    }
}
