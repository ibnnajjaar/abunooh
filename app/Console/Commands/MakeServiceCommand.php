<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {model}';
    protected $description = 'Creates a service class for the given model';

    public function handle()
    {
        $model = $this->argument('model');
        Artisan::call('make:dto', ['model' => $model]);

        $model_name = str($model)->studly()->singular()->replace(['-', '_'], '')->toString();
        $service_class_name = str($model)->studly()->replace(['-', '_'], '')->toString() .'Service';
        $data_object_class_name =  str($model)->studly()->replace(['-', '_'], '')->toString() . 'Data';
        $data_object_variable = str($model)->snake()->singular()->toString() . '_data';
        $model_name_variable = str($model)->snake()->singular()->toString();
        $destination_path = app_path('Services/' . $service_class_name . '.php');
        $stub_path = resource_path('stubs/service.stub');
        $stub_content = File::get($stub_path);

        $content = str_replace(
            ['{{ ModelName }}', '{{ DataObjectName }}', '{{ ServiceClassName }}', '{{ $model_name_variable }}', '{{ $data_object_variable }}'],
            [$model_name, $data_object_class_name, $service_class_name, $model_name_variable, $data_object_variable],
            $stub_content
        );

        $directory = app_path('Services');
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($destination_path)) {
            $this->error('Service already exists');
            return;
        }

        File::put($destination_path, $content);
        $this->info('Service created successfully.');
    }
}
