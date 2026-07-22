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
            'projects' => [
                'projects.view',
                'projects.create',
                'projects.update',
                'projects.delete',
            ],
            'tags' => [
                'tags.view',
                'tags.create',
                'tags.update',
                'tags.delete',
            ],
            'user' => [
                'user.view',
                'user.create',
                'user.update',
                'user.delete.own',
                'user.delete.any',
                'user.force_delete.own',
                'user.force_delete.any',
            ],
            'role' => [
                'role.view',
                'role.create',
                'role.update',
                'role.delete',
            ],
            'settings' => [
                'settings.view',
                'settings.update',
            ]
        ];
    }
}
