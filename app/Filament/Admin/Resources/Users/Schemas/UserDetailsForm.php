<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;

class UserDetailsForm
{
    public static function render(): array
    {
        return [
            Section::make('User Details')
            ->schema([
                TextInput::make('name')
                    ->inlineLabel()
                    ->required(),
                TextInput::make('email')
                    ->inlineLabel()
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->inlineLabel(),

            ])
            ->columnSpan(1)
        ];
    }
}
