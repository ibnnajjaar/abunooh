<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;

use App\Support\Enums\EmployeeStatus;
use function Laravel\Prompts\password;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'hussain.afeef@ium.edu.mv')->first();
        if (! $user) {
            User::create([
                'email' => 'hussain.afeef@ium.edu.mv',
                'password' => password('miee varah dhigu password eh'),
                'name' => 'Administrator',
            ]);
        }
    }
}
