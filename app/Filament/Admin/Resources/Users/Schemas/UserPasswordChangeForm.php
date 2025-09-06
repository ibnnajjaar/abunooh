<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class UserPasswordChangeForm
{
    public static function render(): array
    {
        return [
            Section::make('Update User Password')
                ->visible(fn () => config('auth.allow_admin_password_login'))
                ->schema([
                    Toggle::make('show_password')
                        ->reactive()
                        ->hidden(),
                    TextInput::make('password')
                        ->inlineLabel()
                        ->password(fn (Get $get) => !$get('show_password'))
                        ->suffixAction(
                            Action::make('togglePasswordVisibility')
                                ->icon(fn (Get $get) => $get('show_password') ? Heroicon::EyeSlash : Heroicon::Eye)
                                ->action(function (Get $get, Set $set) {
                                    $current_show_password_status = $get('show_password');
                                    $set('show_password', !$current_show_password_status);
                                })
                        )
                        ->requiredWith('password_confirmation')
                        ->dehydrated(),
                    TextInput::make('password_confirmation')
                        ->inlineLabel()
                        ->password()
                        ->revealable()
                        ->requiredWith('password')
                        ->dehydrated(),
                    Action::make('generate')
                        ->label('Generate Password')
                        ->action(function (Get $get, Set $set) {
                            $rand = Str::random(8);
                            $set('password', $rand);
                            $set('password_confirmation', $rand);
                            $set('show_password', true);
                        }),
                    Checkbox::make('require_password_update_on_next_login'),
                    Checkbox::make('email_password')->label('Send password by email'),
                ])
                ->columnSpan(1)
        ];
    }
}
