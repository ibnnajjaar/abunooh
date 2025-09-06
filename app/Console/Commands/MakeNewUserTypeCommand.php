<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeNewUserTypeCommand extends Command
{
    protected $signature = 'make:new-user-type';

    protected $description = 'Makes new user type';

    public function handle()
    {
        $user_type = $this->ask('Enter the name of the user type');

    }
}
