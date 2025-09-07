<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
                'password' => \Hash::make('password'),
                'name' => 'Administrator',
            ]);
        }
    }
}
