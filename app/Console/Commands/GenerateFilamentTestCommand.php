<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateFilamentTestCommand extends Command
{
    protected $signature = 'generate:filament-test {model}';

    protected $description = 'Generate a Filament test file for a model';

    public function handle(): void
    {
        $model_name = $this->argument('model');
        $stub = file_get_contents(app_path('Support/TestGeneration/test.stub'));
        $target_file_name = 'Admin' . str($model_name)->snake()->title()->plural()->replace('_', '') . 'Test.php';
        $target_file_path = base_path('tests/Feature/Admin/' . $target_file_name);

        $stub = str_replace([
            '{{ ModelPluralLowerNormal }}',
            '{{ ModelPluralTitleNormal }}',
            '{{ ModelPluralLowerKebab }}',
            '{{ ModelSingularTitle }}',
            '{{ ModelSingularCamel }}',
            '{{ ModelPluralSnake }}',
        ], [
            str($model_name)->snake()->plural()->lower()->replace('_', ' ')->toString(),
            str($model_name)->snake()->title()->plural()->replace('_', ' ')->toString(),
            str($model_name)->snake()->plural()->replace('_', '-')->toString(),
            str($model_name)->snake()->title()->singular()->replace('_', '')->toString(),
            str($model_name)->snake()->camel()->singular()->replace('_', '')->toString(),
            str($model_name)->snake()->plural()->toString(),
        ], $stub);
        file_put_contents($target_file_path, $stub);
    }
}
