<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeCustomController extends Command
{
    protected $signature = 'make:custom-controller {name}';
    protected $description = 'Create a new custom controller';
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $namespace = 'App\\Http\\Controllers';
        $rootNamespace = 'App\\';
        $classLowercase = strtolower($name);

        $stub = $this->files->get(base_path('stubs/MyController.stub'));
        $stub = str_replace(
            ['{{ namespace }}', '{{ rootNamespace }}', '{{ class }}', '{{ class_lowercase }}', '{{ repo }}'],
            [$namespace, $rootNamespace, $name, $classLowercase, 'SomeRepositoryInterface'],
            $stub
        );

        $path = app_path("Http/Controllers/{$name}.php");

        if ($this->files->exists($path)) {
            $this->error("Controller {$name} already exists!");
            return;
        }

        $
