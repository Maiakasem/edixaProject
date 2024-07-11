<?php

namespace App\Http\Controllers;

use App\Helpers\Dashboard\MigrationHelper;
use App\Helpers\Dashboard\ModelHelper;
use App\Models\QuickMaker;
use App\Models\QuickMakerColumn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
        
        DB::beginTransaction();
        try {
            $columnStrings      = [];
            $columns            = [];
            $translatable       = [];
            $searchable         = [];
            $hasTranslationTable = false;
            $moduleName = $request->name;
            $modelName = Str::studly(Str::singular($moduleName));
            $base = QuickMaker::create($this->baseData($request));
            foreach ($request->get('group-a') as $column) {
                $type               = $column['type'];
                $columnName         = $column['name'];
                if (isset($column['translatable']) && $column['translatable'] == '1') {
                    $translatable[] = $column['name'];
                    $hasTranslationTable = true;
                } else {
                    $columns[] = $column['name'];
                }
                if (isset($column['searchable']) && $column['searchable'] == '1') {
                    $searchable[] = $column['name'];
                }
                if ($column['relation'] != "0" && $column['relation']  != 0) {
                    if ($column['type'] == 'belongsTo') {
                        $relation_key = $column['relation_key'];
                        $table = Str::plural(Str::lower(class_basename($column['relation_model'])));
                        $columnStrings[] = "\$table->foreignId('$relation_key');";
                        $columnStrings[] = "\$table->foreign('$relation_key')->references('id')->on('$table')->cascadeOnUpdate()->cascadeOnDelete();";
                    }
                    
                } else {
                    $columnString = !empty($column['value']) && $column['value'] !== null ? "\$table->$type('$columnName', {$column['value']})" : "\$table->$type('$columnName')";
                    if(!isset($column['required']) || $column['required'] == '0') {
                        $columnString .= '->nullable()';
                        
                    }
                    if($column['unique'] == '1') {
                        $columnString .= '->unique()';
                        
                    }
                    $columnString .= ';';
                    $columnStrings[] = $columnString;
                }
                QuickMakerColumn::create([
                    'quick_maker_id'    => $base->id,
                    'name'              => $column['name'],
                    'type'              => $column['type'],
                    'required'          => $column['required'] ?? 0,
                    'unique'            => $column['unique'] ?? 0,
                    'searchable'        => $column['searchable'] ?? 0,
                    'translatable'      => $column['translatable'] ?? 0,
                    'relation'          => $column['relation'] ?? 0,
                    'relation_model'    => $column['relation_model'] ?? null,
                    'relation_key'      => $column['relation_key'] ?? null
                ]);
            }
            $migration = new MigrationHelper();
            $migration = $migration->createMigration($request->name, $columnStrings);
            $columns = array_map(function($item) {
                return "'{$item}'";
            }, $columns);
            $searchable = array_map(function($item) {
                return "'{$item}'";
            }, $searchable);
            $translatable = array_map(function($item) {
                return "'{$item}'";
            }, $translatable);
            $model = new ModelHelper();
            $model = $model->createModel($request->name, $searchable, $columns, $translatable);
            DB::commit();

            Artisan::call('migrate');
            Artisan::call('make:custom-repository '.$modelName);
            Artisan::call('make:custom-service '.$modelName);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
        
        



    }
    public function baseData($request) : array {
        return [
            'name'  => $request->name,
            'module'  => $request->module,
        ];
    }
    
}
