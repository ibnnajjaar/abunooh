<?php

namespace App\Filament\Admin\Resources\Activities\Tables;

use App\Models\Activity;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn($query) => $query->orderBy('created_at', 'desc')->with(['causer', 'subject'])
            )
            ->columns([
                TextColumn::make('formatted_created_at')
                          ->label('Time'),
                TextColumn::make('ip')
                          ->label('IP Address')
                          ->searchable(),
                TextColumn::make('description'),
                TextColumn::make('causer.name')
                          ->url(fn(Activity $activity) => $activity->causer?->admin_url)
                          ->color('primary')
                          ->label('Causer')
                          ->description(fn(Activity $activity) => $activity->formatted_causer_meta)
                          ->searchable(),
                TextColumn::make('subject.name')
                          ->url(fn(Activity $activity) => $activity->subject?->admin_url)
                          ->color('primary')
                          ->description(fn(Activity $activity) => $activity->formatted_subject_meta)
                          ->searchable(),
            ])
            ->filters([
                SelectFilter::make('causer_type')
                            ->options([
                                'user' => 'User',
                            ]),
                Filter::make('causer')
                      ->schema([
                          TextInput::make('causer_id'),
                      ])
                      ->query(function (Builder $query, array $data) {
                          return $query
                              ->when(
                                  $data['causer_id'],
                                  fn(Builder $query, $causer_id): Builder => $query->where('causer_id', $causer_id)
                              );
                      }),
                SelectFilter::make('subject_type')
                            ->options([
                                'user'       => 'User',
                                'role'       => 'Role',
                                'permission' => 'Permission',
                            ]),
                Filter::make('subject')
                      ->schema([
                          TextInput::make('subject_id'),
                      ])
                      ->query(function (Builder $query, array $data) {
                          return $query
                              ->when(
                                  $data['subject_id'],
                                  fn(Builder $query, $subject_id): Builder => $query->where('subject_id', $subject_id)
                              );
                      }),
                Filter::make('created_at')
                      ->schema([
                          DatePicker::make('created_from'),
                          DatePicker::make('created_until'),
                      ])
                      ->query(function (Builder $query, array $data): Builder {
                          return $query
                              ->when(
                                  $data['created_from'],
                                  fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                              )
                              ->when(
                                  $data['created_until'],
                                  fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                              );
                      })->columns(2)->columnSpan(2),

            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::TwoExtraLarge)
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
