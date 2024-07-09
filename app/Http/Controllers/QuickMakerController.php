<?php

namespace App\Http\Controllers;

use App\Models\QuickMaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;

class QuickMakerController extends Controller
{
    public function create()
    {
        $types = json_decode(File::get(public_path('dashboard/database_columns_types.json')));
         // Get All Modules
        $modules = Module::all();
        // Get All Models
        $models = $this->getAllModels();
        return view('admin.quick-maker.create', compact('types', 'modules', 'models'));
    }
    public function getAllModels()
    {
        $models = [];
        $modelsPath = app_path('Models'); // For Laravel 8+ structure. Use app_path() for older versions.

        // Scan the Models directory for model files
        $modelFiles = \File::allFiles($modelsPath);
        foreach ($modelFiles as $modelFile) {
            // Get the model class name based on the file name
            $model = $this->getModelNameFromPath($modelFile->getPathname());

            if (class_exists($model)) {
                $models[] = $model;
            }
        }
        return $models;
    }

    protected function getModelNameFromPath($path)
    {
        // Convert the file path into a fully qualified class name
        $class = str_replace([base_path(), '/', '.php'], ['', '\\', ''], $path);

        // Ensure the first segment (App) is capitalized
        $class = implode('\\', array_map(function ($part) {
            return ucfirst($part);
        }, explode('\\', $class)));
        return $class;

    }
    public function store(Request $request)
    {
        dd($request->all());
        $columnStrings = [];
        // $columnStrings[] = "\$table->$method('$columnName');";

        foreach ($request->get('group-a') as $column) {
            if ($column['relation'] != "0" && $column['relation']  != 0) {
                
            } else {
               /*  if (!empty($column['value']) && $column['value'] !== null) {
                    $columnStrings[] = "\$table->$method('$columnName', {$options['column_type_value']});";
                } else {
                    $columnStrings[] = "\$table->$method('$columnName');";
                } */
            }

            
        } 

        DB::beginTransaction();
        try {
            // Prepare Base
            
            $base = QuickMaker::create($this->baseData($request));
        
            // Prepare Columns

            

            
            if ($request->is_module_child != "0" && $request->is_module_child != 0) {
            
                $this->preparingModelAndMigration($request);
            
            }
        
        } catch (\Throwable $th) {
        
        }
        
        



    }
    public function baseData($request) : array {
        return [
            'name'  => $request->name,
            'has_migration'  => $request->has_migration,
            'has_model'  => $request->has_model,
            'has_controller'  => $request->has_controller,
            'has_blade'  => $request->has_blade,
            'has_module'  => $request->is_module_child,
            'module'  => $request->module,
            
            
            //  	 	 	 	has_module 	
        ];
    }
    public function preparingModelAndMigration($request) 
    {
        $hasMigration   = $request->has_migration;
        
        $hasModel       = $request->has_model;
        
        if($hasMigration == '1' && $hasModel == '1') {
        
            // Call Artisan Facade to create model and migration
        
            $this->createModel($request, true);
        
        } else if($hasMigration == '0' && $hasModel == '1') {
        
            $this->createModel($request, false);
        
        } else if($hasMigration == '1' && $hasModel == '0') {
        
            $this->createMigration($request->name);
        
        }        
    }
    public function createMigration($name) {
        $name = \Str::plural($name);
        // convert the name to snake case by Str Facade
        $name = \Str::snake($name);
        
        Artisan::call('make:migration', [
            'name' => 'create_'.$name.'_table',
        ]);
    }
    public function createModel($request, $migration = true)
    {
        Artisan::call('make:model', [
            'name' => $request->name,
            '--migration' => $migration,
        ]);
    }
}
