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
                'relation_display' => $column->relation_display ?? 'name',
            ];
        })->toArray();
        $output .= "@extends('layouts.admin')";
        $output .= " @section('content')";
            foreach ($columnsArray as $column) {

                $output .= '<div class="col-12 col-md-6 px-0 d-flex mb-3">
                            <div class="col-3 px-2 text-start pt-1">' .
                    "{{__('lang.". $column['name'] . "')}}";
                $output .= '</div> <div class="col-9 px-2">';
                if ($column['relation_model'] == null) {
                    if ($column['type']  == 'text') {
                        $output .= '<textarea type="' . $column['type'] . '" name="' . $column['name'] . '" class="form-control"';
                        if ($column['required']) {
                            $output .= ' required';
                        }
                        $output .= ">{{old('" . $column['name'] . "')}}</textarea>";
                    } else {
                        $output .= '<input type="' . $column['type'] . '" name="' . $column['name'] . '" class="form-control" value="{{old(' . "'" . $column['name'] . "'" . ')}}"';
                        if ($column['required']) {
                            $output .= ' required';
                        }
                        $output .= '>';
                    }
                } else {
                    $output .= '<select  name="' . $column['name'] . '" class="form-select"';
                    if ($column['required']) {
                        $output .= ' required';
                    }
                    $output .= '>';
                    $output .=         '<option value="">Choose</option>';
                    $output .=     '@foreach(' . $column['relation_model'] . '::get() as $item)';
                    $output .=         '<option value="{{ $item->id }}">{{ $item->' . $column['relation_display'] . ' }}</option>';
                    $output .=     '@endforeach';
                    $output .= '</select>';
                }
                $output .= '</div>';
                $output .= '</div>';
            }
        $output .= "@endsection";


        File::put(resource_path('views/generated.blade.php'), $output);
        return  view('generated');
    }
}
