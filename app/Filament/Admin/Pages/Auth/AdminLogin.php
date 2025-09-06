<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\RenderHook;

class AdminLogin extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        if (app()->environment('local')) {
            $this->form->fill([
                'email' => 'hussain.afeef@ium.edu.mv',
                'password' => 'miee varah dhigu password eh',
                'remember' => true,
            ]);
        }
    }

    public function content(Schema $schema): Schema
    {
        $components = [
            RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE)
        ];

        if (config('auth.allow_admin_password_login')) {
            $components[] = $this->getFormContentComponent();
            $components[] = $this->getMultiFactorChallengeFormContentComponent();
        }

        $components[] = RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER);

        return $schema->components($components);
    }

}
