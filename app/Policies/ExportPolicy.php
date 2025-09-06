<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Filament\Actions\Exports\Models\Export;

class ExportPolicy
{
    public function view(User $user, Export $export): bool
    {
        return true;
    }
}
