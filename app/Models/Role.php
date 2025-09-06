<?php

namespace App\Models;

use App\Support\Traits\HasActivityLogs;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasActivityLogs;

}
