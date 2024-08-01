<?php

namespace App\Http\Controllers;

use App\Models\QuickMaker;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\QuickMakerColumn;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\File;
use App\Helpers\QuickMaker\BladeHelper;
use App\Helpers\QuickMaker\ModelHelper;
use App\Helpers\QuickMaker\RouteHelper;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\QuickMaker\RequestHelper;
use App\Helpers\QuickMaker\MigrationHelper;
use App\Helpers\QuickMaker\PermissionsHelper;

class QuickMakerController extends Controller
{
    public function index()
    {
        return view('admin.quick-maker.index');
    }
    public function create_module(Request $request) 
    {
        Artisan::call('module:make '.$request->name);
        return redirect()->route('admin.quick-makers.create');

    }
    public function create()
    {
        $types = json_decode(File::get(public_path('dashboard/database_columns_types.json')));
         // Get All Modules
        $modules = Module::all();
        // Get All Models
        $models = $this->getAllModels();
        $inputTypes = [
            "checkbox"          => [
                'required',
                'accepted',
                'nullable'
            ],
            "color"             => [
                'required',
                'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
                'nullable'
            ],
            "date"              => [
                'required',
                'date',
                'before:now',
                'before:today',
                'before:yesterday',
                'after_or_equal:now',
                'after_or_equal:today',
                'after_or_equal:yesterday',
                'nullable'
            ],
            "datetime-local"    => [
                'date_format:Y-m-d\TH:i',
                'required',
                'nullable',
                'date',
                'before:now',
                'before:today',
                'before:yesterday',
                'after_or_equal:now',
                'after_or_equal:today',
                'after_or_equal:yesterday'
            ],
            "email"             => [
                'required',
                'email',
                'exists',
                'max',
                'nullable',
                'unique'
            ],
            "file"              => [
                'required',
                'mimes',
                'min',
                'max',
                'nullable'
            ],
            "image"             => [
                'required',
                'nullable',
                'mimes',
                'min',
                'max',
                'image'
            ],
            "month"             => [
                'required',
                'date_format:Y-m',
                'nullable',
            ],
            "number"            => [
                'required',
                'integer',
                'nullable',
                'between'
            ],
            "password"          => [
                'required',
                'string',
                'min',              // Minimum length of 8 characters
                'confirmed',          // Requires a matching password_confirmation field
                'regex:/[a-z]/',      // Must contain at least one lowercase letter
                'regex:/[A-Z]/',      // Must contain at least one uppercase letter
                'regex:/[0-9]/',      // Must contain at least one digit
                'regex:/[@$!%*?&#]/', // Must contain a special character
            ],
            "tel"   => [
                'required',
                'string',
                'regex:/^\+?[0-9]{10,15}$/', // Allows optional + at the start, followed by 10-15 digits
                'nullable',
                'unique'
            ],
            "text"      => [
                'required',
                'string',
                'min',
                'max',
                'nullable',
                'unique'
            ],
            "time"      => [
                'required',
                'nullable',
                'date_format:H:i'
            ],
            "url"       => [
                'required',
                'nullable',
                'url',
                'unique'
            ],
            "week"      => [
                'required',
                'nullable',
                'date_format:Y-\WW'
            ]
        ];

        return view('admin.quick-maker.create', compact('types', 'modules', 'models', 'inputTypes'));
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
        $formData                   = json_decode($request->formData);
        $className                  = $formData[0]->className;
        $is_module_child            = $formData[1]->is_module_child;
        $module                     = $formData[2]->module;
        $columnsData                = $formData[3]->columns;



        if(!$is_module_child) {
            $columnStrings          = [];
            $columns                = [];
            $translatableArr           = [];
            $searchableArr             = [];
            $validationsArr             = [];
            $hasTranslationTable    = false;
            $modelName = Str::studly(Str::singular($className));
            $base = QuickMaker::firstOrCreate($this->baseData($className, $module ?? null));
            foreach ($columnsData as $column) {
                $validationArr             = [];
                // Start Database
                $database               = $column[0]->database;
                $columnName             = $database[0]->columnName;
                $columnValue            = $database[1]->columnValue;
                $columnType             = $database[2]->columnType;
                // End Database
                // Start Validations
                $validations            = $column[3]->validations;
               // dd($validations);
                $blade_type             = $validations[0]->blade_type;
                $checkboxes             = $validations[1]->checkboxes;
                $values                 = $validations[2]->values;
                if (!empty($checkboxes)) {
                    foreach ($checkboxes as $key => $rule) {
                        $validationArr[] = $rule; 
                    }
                }
                if (!empty($values)) {
                    foreach ($values as $key => $rule) {
                        foreach ($rule as $key => $value) {
                            $validationArr[] = $key.":".$value; 
                        }
                    }
                }
               
                $validationsArr[$columnName] = $validationArr;
                // End Validations
                
                // Start Spacial
                $spacial            = $column[1]->spacial;
                $searchable         = $spacial[0]->searchable;
                $translatable       = $spacial[1]->translatable;
                // Start Spacial

                // Start Relationship
                $relationship       = $column[2]->relationship;
                $relation           = $relationship[0]->relation;
                $relation_model     = $relationship[1]->relation_model;
                $relation_key       = $relationship[2]->relation_key;
                $relation_display   = $relationship[3]->relation_display ?? null;
                
                // End Relationship
                
                if (isset($translatable) && $translatable) {
                    $translatableArr[] =  $columnName;
                    $hasTranslationTable = true;
                } else {
                    $columns[] =  $columnName;
                }
                if (isset($searchable) && $searchable) {
                    $searchableArr[] = $columnName;
                }
                if ($relation) {
                    if ($columnType == 'belongsTo') {
                        $table = Str::plural(Str::lower(class_basename($relation_model)));
                        $columnStrings[] = "\$table->foreignId('$relation_key');";
                        $columnStrings[] = "\$table->foreign('$relation_key')->references('id')->on('$table')->cascadeOnUpdate()->cascadeOnDelete();";
                    }
                } else {
                    $columnString = !empty($columnValue) && $columnValue !== null ? "\$table->$columnType('$columnName', {$columnValue})" : "\$table->$columnType('$columnName')";
                    $columnString .= in_array('unique', $validationsArr[$columnName]) ? '->unique()' : '';
                    $columnString .= !in_array('required', $validationsArr[$columnName]) ? '->nullable()' : '';
                    $columnString .= in_array($columnName, $searchableArr) ? '>index()' : ''; 

                    
                    $columnString .= ';';
                    $columnStrings[] = $columnString;
                }
                QuickMakerColumn::create([
                    'quick_maker_id'    => $base->id,
                    'name'              => $columnName,
                    'type'              => $columnType,
                    'searchable'        => $searchable,
                    'translatable'      => $translatable,
                    'relation'          => $relation,
                    'relation_model'    => $relation_model,
                    'relation_key'      => $relation_key,
                    'relation_display'  => $relation_display,
                    'blade_type'        => $blade_type
                ]);
            }
            $makeRequest = new RequestHelper();
            $makeRequest->createStoreFormRequest($validationsArr, $className);

            $makeRequest = new RequestHelper();
            $makeRequest->createUpdateFormRequest($validationsArr, $className);

            $migration = new MigrationHelper();
            $migration = $migration->createMigration($modelName, $columnStrings);
            $columns = array_map(function($item) {
                return "'{$item}'";
            }, $columns);
            $searchableArr = array_map(function($item) {
                return "'{$item}'";
            }, $searchableArr);
            $translatableArr = array_map(function($item) {
                return "'{$item}'";
            }, $translatableArr);
            $model = new ModelHelper();
            $model = $model->createModel($modelName, $searchableArr, $columns, $translatableArr);

            $createPermissions = new PermissionsHelper();
            $createPermissions->createPermissions($modelName);

            Artisan::call('make:custom-repository '.$modelName);
            Artisan::call('migrate');
            Artisan::call('make:custom-repository '.$modelName);
            Artisan::call('make:custom-service '.$modelName);
            Artisan::call('make:custom-controller '.$modelName);
            // Artisan::call('make:custom-api-controller '.$modelName);
        }
    }
    public function baseData($name, $module = null) : array {
        return [
            'name'  => $name,
            'module'  => $module,
        ];
    }



}
