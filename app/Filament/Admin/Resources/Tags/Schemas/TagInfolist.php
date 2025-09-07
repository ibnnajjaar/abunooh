<?php

namespace App\Filament\Admin\Resources\Tags\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\ColorEntry;

class TagInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tag Details')
                       ->schema([
                           TextEntry::make('name')->inlineLabel(),
                           TextEntry::make('slug')->inlineLabel(),
                           ColorEntry::make('color')
                                     ->copyable()
                                     ->helperText(fn(string $state): string => $state)
                                     ->inlineLabel(),
                           TextEntry::make('created_at')->inlineLabel()->dateTime(),
                           TextEntry::make('updated_at')->inlineLabel()->dateTime(),
                       ]),
            ])
            ->columns(1);
    }
}
