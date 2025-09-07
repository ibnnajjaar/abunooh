<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use Filament\Schemas\Schema;
use App\Support\Enums\PublishStatuses;
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
                               ->color(fn ($state): string => PublishStatuses::getColor(is_string($state) ? $state : ($state?->value ?? '')))
                               ->formatStateUsing(fn ($state): string => is_string($state) ? str($state)->title() : (method_exists($state, 'value') ? str($state->value)->title() : ''))
                               ->inlineLabel(),
                           TextEntry::make('description')->inlineLabel()->markdown(),
                           TextEntry::make('created_at')->inlineLabel()->dateTime(),
                           TextEntry::make('updated_at')->inlineLabel()->dateTime(),
                       ]),
            ])
            ->columns(1);
    }
}
