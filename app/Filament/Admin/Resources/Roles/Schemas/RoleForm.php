<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\CheckboxList;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role Details')
                       ->schema([
                           TextInput::make('name')
                                    ->inlineLabel()
                                    ->required(),
                           TextInput::make('description')
                                    ->inlineLabel(),
                       ])->columnSpan(1),
                Section::make('Permissions')
                       ->schema([
                           CheckboxList::make('permissions')
                                       ->hiddenLabel()
                                       ->relationship('permissions', 'name')
                                       ->getOptionLabelFromRecordUsing(fn ($record) => str($record->name)->title())
                                       ->searchable()
                                       ->searchPrompt('Start typing to search for permissions...')
                                       ->noSearchResultsMessage('No permissions found.')
                                       ->bulkToggleable()
                                       ->columns(2),
                       ]),
            ])->columns(1);
    }
}
