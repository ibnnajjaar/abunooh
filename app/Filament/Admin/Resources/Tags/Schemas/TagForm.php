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
                ->columnSpanFull()
                ->schema([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('slug')
                        ->unique(ignoreRecord: true),
                    ColorPicker::make('color')
                        ->nullable(),
                ]),
        ];
    }
}
