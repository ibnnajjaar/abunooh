<?php

namespace App\Filament\Admin\Resources\Activities\Schemas;

use App\Models\Activity;
use Filament\Schemas\Schema;
use App\Livewire\ChangeLogList;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Livewire;
use Filament\Infolists\Components\TextEntry;

class ActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Activity Log')
                       ->schema([
                           TextEntry::make('created_at')->inlineLabel()
                                    ->label('Time'),
                           TextEntry::make('ip')
                                    ->label('IP Address')
                                    ->inlineLabel(),
                           TextEntry::make('causer.name')->label('Causer')
                                    ->color('primary')
                                    ->url(fn (Activity $record) => $record->causer?->admin_url)
                                    ->inlineLabel(),
                           TextEntry::make('subject.name')
                                    ->url(fn (Activity $record) => $record->subject?->admin_url)
                                    ->color('primary')
                                    ->label('Subject')
                                    ->inlineLabel(),
                           TextEntry::make('description')
                                    ->inlineLabel(),
                       ]),
                Livewire::make(ChangeLogList::class),
            ])->columns(1);
    }
}
