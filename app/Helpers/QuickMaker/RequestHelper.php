<?php

namespace App\Helpers\QuickMaker;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

class RequestHelper
{
    private function createValidationRules($columns, $tableName) : array {

        $validation = [];
        foreach ($columns as $column) {
            $rules = [];
            dd($column);
            if($column['required'] == '1') {
                $rules[] = 'required';
            }
            if($column['unique'] == '1') {
                $rules[] = 'unique:'.Str::snake(Str::plural($tableName));
            }
            if($column['type'] == 'string') {
                $rules[] = 'string';
            }
            if($column['type'] == 'string' && $column['value'] != null) {
                $rules[] = 'max:'.$column['value'];
            }
            if($column['relation'] == '1') {
                $rules[] = 'numeric';
                $modelClassName = $column['relation_model'];
                if (class_exists($modelClassName)) {
                    // Instantiate the model
                    $modelInstance = App::make($modelClassName);

                    // Get the table name
                    $tableName = $modelInstance->getTable();
                    $rules[] = 'exists:'.$tableName.',id';

                }
            }

            $validation[$column['name']] = $rules;

        }
        return $validation;
    }
    public function createStoreFormRequest($columns, $tableName)
    {
        $validation = $this->createValidationRules($columns, $tableName);
        dd($validation);
        return [];
    }
    /**
     * Create a new class instance.
     */
/*     public function createStoreRequest($modelName) {
        $modelName = Str::studly(Str::singular($modelName)).'StoreRequest';
        if ($this->modelExists($modelName)) {
            return [
                'message' => 'Request already exists for : ' . $modelName,
                'success' => false
            ];
        }
        $stubPath = base_path('stubs/custom/request.stub');

        $fileName = $modelName . '.php';
        $ModelPath = app_path('Http/Requests/' . $fileName);

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
 */
}
