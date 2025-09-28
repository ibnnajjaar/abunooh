<?php

namespace App\Filament\Admin\Resources\Posts\Tables;

use Filament\Tables\Table;
use App\Support\Enums\PostTypes;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use App\Support\Enums\PublishStatuses;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => $state->getColor())
                    ->formatStateUsing(fn ($state): string => $state->getLabel())
                    ->sortable(),
                TextColumn::make('post_type')
                    ->badge()
                    ->color(fn ($state): string => $state->getColor())
                    ->formatStateUsing(fn ($state): string => $state->getLabel())
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Could add status/post_type filters in future
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
