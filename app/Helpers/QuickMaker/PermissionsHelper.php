<?php

namespace App\Helpers\QuickMaker;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;

class PermissionsHelper
{
    public array $crud = [];

    public function __construct()
    {
        $this->crud = ['create', 'read', 'update', 'delete'];
    }
    /**
     * Create a new class instance.
     */
    public function createPermissions($modelName) : void
    {
        $tableName = Str::kebab(Str::plural($modelName));
        foreach ($this->crud as $crud) {
            if(Permission::where('name','=',$tableName.'-'.$crud)->first() == null) {
                Permission::create([
                    'name'=>$tableName.'-'.$crud,
                    'table'=>$tableName,
                ]);
            }
        }
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

    }

}
