<?php

namespace App\Helpers\QuickMaker;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

class RequestHelper
{
    public function createStoreFormRequest($columns, $tableName)
    {
        $modelName = Str::studly(Str::singular($tableName)).'StoreRequest';
        $kebab = Str::kebab(Str::plural($tableName));
        if ($this->requestExists($modelName)) {
            return [
                'message' => 'Request already exists for : ' . $modelName,
                'success' => false
            ];
        }
        $validationString = implode(",\n    ", array_map(function ($key, $rules) {
            $rulesString = implode(",\n        ", array_map(function ($rule) {
                return "'{$rule}'";
            }, $rules));
            return "'{$key}' => [\n        {$rulesString}\n    ]";
        }, array_keys($columns), $columns));
       // dd($validationString);

    

        $stubPath = base_path('stubs/custom/store-request.stub');
        $stubContent = File::get($stubPath);

        // Replace the placeholder with the actual validation rules
        $stubContent = str_replace('{{ $modelName }}', $modelName, $stubContent);
        $stubContent = str_replace('{{ $validations }}', $validationString, $stubContent);
        $stubContent = str_replace('{{ $className }}', $kebab, $stubContent);

        // Save the modified content to a new file (or do whatever you need with it)
        $outputPath = app_path('Http/Requests/'.$modelName.'.php');
        File::put($outputPath, $stubContent);

        return [
            'message' => 'request successfully created',
            'success' => true
        ];
    }



    public function createUpdateFormRequest($columns, $tableName) 
    {
        $modelName = Str::studly(Str::singular($tableName)).'UpdateRequest';
        $kebab = Str::kebab(Str::plural($tableName));
        if ($this->requestExists($modelName)) {
            return [
                'message' => 'Request already exists for : ' . $modelName,
                'success' => false
            ];
        }
        $validationString = implode(",\n    ", array_map(function ($key, $rules) {
            $rulesString = implode(",\n        ", array_map(function ($rule) {
                return "'{$rule}'";
            }, $rules));
            return "'{$key}' => [\n        {$rulesString}\n    ]";
        }, array_keys($columns), $columns));

    

        $stubPath = base_path('stubs/custom/update-request.stub');
        $stubContent = File::get($stubPath);

        // Replace the placeholder with the actual validation rules
        $stubContent = str_replace('{{ $modelName }}', $modelName, $stubContent);
        $stubContent = str_replace('{{ $validations }}', $validationString, $stubContent);
        $stubContent = str_replace('{{ $className }}', $kebab, $stubContent);

        // Save the modified content to a new file (or do whatever you need with it)
        $outputPath = app_path('Http/Requests/'.$modelName.'.php');
        File::put($outputPath, $stubContent);

        return [
            'message' => 'request successfully created',
            'success' => true
        ];
    }
    private function requestExists($modelName)
    {
        $modelFiles = File::files(app_path('Http/Requests'));
        foreach ($modelFiles as $file) {
            $modelName = Str::studly(Str::singular($modelName));
            if (Str::contains($file->getFilename(), $modelName)) {
                return true;
            }
        }
        return false;
    }
 
}
