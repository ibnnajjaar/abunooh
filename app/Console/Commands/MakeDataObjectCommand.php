<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDataObjectCommand extends Command
{
    protected $signature = 'make:dto
                            {model : The name of the DTO model}';
    protected $description = 'Make a data object class for given model';

    public function handle(): void
    {
        $model = $this->argument('model');
        $class_name =  str($model)->studly()->replace(['-', '_'], '')->toString() . 'Data';
        $destination_path = app_path('DataObjects/' . $class_name . '.php');
        $stub_path = resource_path('stubs/dto.stub');

        if (File::exists($destination_path)) {
            $this->error('Data object already exists');
        }

        if (! File::isDirectory(app_path('DataObjects'))) {
            File::makeDirectory(app_path('DataObjects'), 0755, true);
        }

        $stub_content = File::get($stub_path);
        $stub_content = str_replace('{{ ClassName }}', $class_name, $stub_content);
        File::put($destination_path, $stub_content);
        $this->info('Data object created successfully');
    }
}
