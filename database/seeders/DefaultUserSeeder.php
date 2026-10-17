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
            $user = User::create([
                'id' => 1,
                'email' => 'ibnahnajjaar@gmail.com',
                'password' => \Hash::make('aharen mi jahanee v gadha password eh'),
                'name' => 'Administrator',
            ]);
        }

        $secondUser = User::find(2);
        if (! $secondUser) {
            User::create([
                'id' => 2,
                'email' => 'author@example.com',
                'password' => \Hash::make('password'),
                'name' => 'Second Author',
            ]);
        }
    }
}
