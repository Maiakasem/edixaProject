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
            ],
            "text"      => [
                'required',
                'string',
                'min',
                'max',
                'nullable',
            ],
            "time"      => [
                'required',
                'nullable',
                'date_format:H:i'
            ],
            "url"       => [
                'required',
                'nullable',
                'url'
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
        $formRequest = new RequestHelper();
        $storeFormRequest = $formRequest->createStoreFormRequest(json_decode($request->validations), $request->name);
        // dd($storeFormRequest);
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
                    'relation_key'      => $column['relation_key'] ?? null,
                    'relation_display'  => $column['relation_display'] ?? null
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

            $createPermissions = new PermissionsHelper();
            $createPermissions->createPermissions($request->name);

            DB::commit();

            Artisan::call('migrate');
            Artisan::call('make:custom-repository '.$modelName);
            Artisan::call('make:custom-service '.$modelName);

            $model = new BladeHelper();
            $model = $model->bladeCreate($column,$request->name);
            // $model = new RouteHelper();
            // $model = $model->routecreate($request->name);


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
