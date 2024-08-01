<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\QuickMaker;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\QuickMakerColumn;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Helpers\QuickMaker\BladeHelper;
use App\Helpers\QuickMaker\RouteHelper;

class BackendTestController extends Controller
{
    public function test(Request $request)
    {
        $command = 'composer create-project laravel/laravel example-app';

        // Execute the command
        $output = shell_exec($command);
    }
}
