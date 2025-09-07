<?php

namespace App\Filament\Admin\Resources\Tags\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\ColorPicker;

class TagForm
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

                       TextInput::make('name')
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
                       ColorPicker::make('color')
                           ->regex('/^#(?:[0-9a-fA-F]{3}){1,2}$/')
                                  ->required(),
                   ]),
        ];
    }
}
