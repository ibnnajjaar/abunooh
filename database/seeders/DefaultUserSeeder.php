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
        $user = User::where('email', 'ibnahnajjaar@gmail.com')->first();
        if (! $user) {
            User::create([
                'email' => 'ibnahnajjaar@gmail.com',
                'password' => \Hash::make('aharen mi jahanee v gadha password eh'),
                'name' => 'Administrator',
            ]);
        }
    }
}
