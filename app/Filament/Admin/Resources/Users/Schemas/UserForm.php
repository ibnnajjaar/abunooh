<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $edit_schema = request()->routeIs('*.create') ? self::renderCreateForm() : self::renderEditForm();
        return $schema
            ->components($edit_schema)->columns(1);
    }

    public static function renderCreateForm(): array
    {
        return [
            ...UserDetailsForm::render(),
            ...UserPasswordChangeForm::render(),
        ];
    }

    public static function renderEditForm(): array
    {
        $tabs = [];
        if (! config('auth.allow_admin_password_login')) {
            $tabs = UserDetailsForm::render();
        }
        if (config('auth.allow_admin_password_login')) {
            $tabs = [
                Tabs::make()
                    ->tabs([
                        Tab::make('User Details')
                           ->schema(UserDetailsForm::render()),
                        Tab::make('Password Change')
                           ->schema(UserPasswordChangeForm::render()),
                    ])
                    ->contained(false)
                    ->persistTabInQueryString(true)
                    ->vertical(),
            ];
        }
        return $tabs;
    }
}
