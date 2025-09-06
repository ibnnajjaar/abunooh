<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = Permission::all()->pluck('name')->toArray();
        $role = Role::where('name', 'admin')->first();

        if (! $role) {
            $role = new Role();
        }
        $role->name = 'admin';
        $role->guard_name = 'web_admin';
        $role->description = 'System Administator';
        $role->syncPermissions($permissions);
        $role->save();

        $user = User::where('email', 'hussain.afeef@ium.edu.mv')->first();
        $user?->roles()->sync([$role->id]);

        Role::updateOrCreate(
            ['name' => 'guest'],
            [
                'guard_name' => 'web',
                'description' => 'Guest User',
            ]
        );
    }
}
