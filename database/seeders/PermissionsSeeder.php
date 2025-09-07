<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getData() as $model => $model_permissions) {
            foreach ($model_permissions as $permission_name) {
                Permission::updateOrCreate([
                    'name' => $permission_name,
                ], [
                    'model'      => $model,
                    'guard_name' => 'web_admin',
                ]);
            }
        }
    }

    public function getData(): array
    {
        return [
            'tags' => [
                'view tags',
                'create tags',
                'update tags',
                'delete tags',
            ],
            'time_entries' => [
                'view time entries',
                'view any time entry',
                'create time entries',
                'update time entries',
                'update any time entry',
                'delete time entries',
                'delete any time entry',
            ],
            'employee' => [
                'view employees',
                'view any employee',
                'create employees',
                'update employees',
                'update any employee',
                'delete employees',
                'delete any employee',
                'force delete employees',
                'force delete any employee',
                'approve employees',
            ],
            'user' => [
                'view users',
                'create users',
                'update users',
                'delete users',
                'delete any user',
                'force delete users',
                'force delete any user',
            ],
            'role' => [
                'view roles',
                'create roles',
                'update roles',
                'delete roles',
            ],
            'settings' => [
                'view settings',
                'edit settings',
            ]
        ];
    }
}
