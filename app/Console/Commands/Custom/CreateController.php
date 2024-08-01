<?php

namespace App\Console\Commands\Custom;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate A Controller Class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('name');
        $namespace = 'App\\Http\\Controllers\\Backend';
        $className = $model . 'Controller';
        $service = $model . 'Service';
        $serviceNamespace = 'App\\Services';
        $permissionName = Str::kebab(Str::plural($model));
        $modelName = Str::studly(Str::singular($model));
        // Check if the model exists, if not, create it
        $controllerPath = app_path("Http/Controllers/Backend/".$className.".php");
        if (!File::exists($controllerPath)) {
            $this->call('make:controller', ['name' => $className]);
        }

        // Create the controller file from the stub
        $stub = File::get(base_path('stubs/custom/controller.stub'));

        $stub = str_replace(
            ['{{ namespace }}', '{{ serviceNamespace }}', '{{ service }}', '{{ class }}','{{ permissionName }}', '{{ modelName }}'],
            [$namespace, $serviceNamespace, $service, $className, $permissionName, $modelName],
            $stub
        );

        File::put($controllerPath, $stub);

        $this->info("Controller created successfully.");
    }
}
