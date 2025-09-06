<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use App\Models\Activity;
use App\Models\Employee;
use App\Policies\ExportPolicy;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Filament\Support\Facades\FilamentView;
use Filament\Actions\Exports\Models\Export;
use App\Filament\Admin\Pages\Auth\AdminLogin;
use App\Observers\DatabaseNotificationObserver;
use Illuminate\Notifications\DatabaseNotification;
use App\Filament\Employee\Pages\Auth\EmployeeLogin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Export::class, ExportPolicy::class);

        DatabaseNotification::observe(DatabaseNotificationObserver::class);

        // enforce morph map for all models
        \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
            'activity'         => Activity::class,
            'user'             => User::class,
            'employee'         => Employee::class,
            'role'             => Role::class,
            'permission'       => Permission::class,
            'time_entry'       => \App\Models\TimeEntry::class,
            'time_entry_break' => \App\Models\TimeEntryBreak::class,
            'shift'            => \App\Models\Shift::class,
            'schedule'         => \App\Models\Schedule::class,
        ]);

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
            fn (): View => view('admin.filament.components.google'),
            scopes: AdminLogin::class,
        );
    }
}
