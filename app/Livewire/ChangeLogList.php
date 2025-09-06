<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Activity;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Support\Contracts\TranslatableContentDriver;

class ChangeLogList extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractswithActions;

    protected Collection $properties;

    public function mount(Activity $record): void
    {
        $properties = $record->properties;
        $changes = collect($properties['attributes'] ?? [])
            ->map(function ($new_value, $key) use ($properties) {
                return [
                    'attribute' => $key,
                    'old' => $properties['old'][$key] ?? null,
                    'new' => $new_value,
                ];
            })->values();
        $this->properties = $changes;
    }

    public function table(Table $table): Table
    {
        return $table->records(
            fn () => $this->properties
        )
            ->heading('Change Log')
            ->description('This is a list of changes made to the record.')
            ->columns([
                TextColumn::make('attribute'),
                TextColumn::make('old'),
                TextColumn::make('new'),
            ])->paginated(false);
    }

    public function render()
    {
        return view('livewire.change-log-list');
    }
}
