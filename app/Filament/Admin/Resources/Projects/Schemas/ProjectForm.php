<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Support\Enums\PublishStatuses;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\MarkdownEditor;

class ProjectForm
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
            Section::make()
                   ->inlineLabel()
                   ->columnSpanFull()
                   ->schema([
                       TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->live(debounce: 1000)
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('slug', $state ? \Illuminate\Support\Str::slug($state) : null);
                                }),
                       TextInput::make('slug')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255)
                                ->readonly(),
                       TextInput::make('client')
                                ->maxLength(255)
                                ->required(),
                       TextInput::make('status')
                                ->maxLength(255)
                                ->required(),
                       TextInput::make('year')
                                ->numeric()
                                ->minValue(1900)
                                ->maxValue((int) date('Y') + 1)
                                ->required(),
                       Select::make('publish_status')
                             ->label('Publish Status')
                             ->searchable()
                             ->options(PublishStatuses::options())
                             ->required(),
                       Select::make('tags')
                             ->label('Tags')
                             ->relationship('tags', 'name')
                             ->multiple()
                             ->preload()
                             ->searchable(),
                   ]),
            Section::make('Description')
                   ->inlineLabel()
                   ->columnSpanFull()
                   ->schema([
                       MarkdownEditor::make('description')
                                     ->columnSpanFull(),
                   ]),
        ];
    }
}
