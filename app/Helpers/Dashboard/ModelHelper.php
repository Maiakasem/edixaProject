<?php

namespace App\Helpers\Dashboard;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ModelHelper
{
    /**
     * Create a new class instance.
     */
    public function createModel($modelName, $searchable, $columns, $translatedAttributes) {
        $modelName = Str::studly(Str::singular($modelName));
        if ($this->modelExists($modelName)) {
            return [
                'message' => 'Model already exists for : ' . $modelName,
                'success' => false
            ];
        }
        $stubPath = base_path('stubs/custom/model.stub');

        $fileName = $modelName . '.php';
        $ModelPath = app_path('Models/' . $fileName);

        // Read the stub file
        $filesystem = new Filesystem;
        $stub = $filesystem->get($stubPath);

        // Replace placeholders
        $stub = str_replace('{{modelName}}', $modelName, $stub);
        $stub = str_replace('{{columns}}', '['.implode(",", $columns).']', $stub);
        $stub = str_replace('{{searchable}}', '['.implode(",", $searchable).']', $stub);
        $stub = str_replace('{{translatedAttributes}}', '['.implode(",", $translatedAttributes).']', $stub);
        // Save the new migration file
        $filesystem->put($ModelPath, $stub);    
        return [
            'message' => 'Model successfully created',
            'success' => true
        ];
    }
    private function modelExists($modelName)
    {
        $modelFiles = File::files(app_path('Models'));
        foreach ($modelFiles as $file) {
            $modelName = Str::studly(Str::singular($modelName));
            if (Str::contains($file->getFilename(), $modelName)) {
                return true;
            }
        }
        return false;
    }
}
