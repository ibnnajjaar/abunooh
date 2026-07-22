<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project Details')
                       ->schema([
                           TextEntry::make('title')->inlineLabel(),
                           TextEntry::make('slug')->inlineLabel(),
                           TextEntry::make('client')->inlineLabel(),
                           TextEntry::make('status')->inlineLabel(),
                           TextEntry::make('year')->inlineLabel(),
                           TextEntry::make('publish_status')
                               ->badge()
                               ->color(fn ($state) => $state?->getColor())
                               ->formatStateUsing(fn ($state) => $state?->getLabel())
                               ->inlineLabel(),
                           TextEntry::make('description')->inlineLabel()->markdown(),
                           TextEntry::make('created_at')->inlineLabel()->dateTime(),
                           TextEntry::make('updated_at')->inlineLabel()->dateTime(),
                       ]),
            ])
            ->columns(1);
    }
}
