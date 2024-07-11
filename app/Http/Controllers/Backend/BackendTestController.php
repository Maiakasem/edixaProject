<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\QuickMaker;
use App\Models\QuickMakerColumn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class BackendTestController extends Controller
{
    public function test(Request $request)
    {
        $quickMaker = QuickMaker::first();
        $columns = QuickMakerColumn::where('quick_maker_id', $quickMaker->id)->get();
        $output = '';

        $columnsArray = $columns->map(function ($column) {
            return [
                'name' => $column->name,
                'type' => $column->type,
                'required' => $column->required,
                'relation' => $column->relation,
                'relation_model' => $column->relation_model,
                'relation_key' => $column->relation_key,
            ];
        })->toArray();

        $output = view('stubs.dynamic_fields', ['columns' => $columnsArray])->render();

        File::put(resource_path('views/generated.blade.php'), $output);

        dd('value');
    }
}
