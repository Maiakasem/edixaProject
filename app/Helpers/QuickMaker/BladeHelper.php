<?php

namespace App\Helpers\QuickMaker;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BladeHelper
{

    public function bladeCreate($columns, $table_name, $modules = null)
    {
        $tableName = Str::snake(Str::plural($table_name));
        $tableName = str_replace('_', '-', $tableName);
        $output = '';
        $output .= "@extends('layouts.admin')\n";
        $output .= "@section('content')\n";
        $output .= "<div class='col-12 py-0 px-3 row'>" . "\n";
        $output .= "    <div class='col-12  pt-4' style='background: #fff;min-height: 80vh'>" . "\n";
        $output .= "        <div class='col-12 px-3'>" . "\n";
        $output .= "            <h4></h4>" . "\n";
        $output .= "        </div>" . "\n";
        $output .= "        <div class='col-12 col-lg-9 px-3 py-5'>" . "\n";
        $output .= "            <form class='col-12' method='POST' action='admin.{$table_name}.store' enctype='multipart/form-data'>" . "\n";
        $output .= "                @csrf" . "\n";
        foreach ($columns as $column) {
            $output .= '<div class="col-12 col-md-6 px-0 d-flex mb-3">' . "\n";
            $output .= '    <div class="col-3 px-2 text-start pt-1">' . "\n";
            $output .= '        {{__(\'lang.' . $column['name'] . '\')}}' . "\n";
            $output .= '    </div>' . "\n";
            $output .= '    <div class="col-9 px-2">' . "\n";
            if ($column['relation_model'] == null) {
                if ($column['type'] == 'text') {
                    $output .= '        <textarea type="' . $column['type'] . '" name="' . $column['name'] . '" class="form-control"';
                    if ($column['required']) {
                        $output .= ' required';
                    }
                    $output .= '>{{old(\'' . $column['name'] . '\')}}</textarea>' . "\n";
                } else {
                    $output .= '        <input type="' . $column['type'] . '" name="' . $column['name'] . '" class="form-control" value="{{old(\'' . $column['name'] . '\')}}"';
                    if ($column['required']) {
                        $output .= ' required';
                    }
                    $output .= '>' . "\n";
                }
            } else {
                $output .= '        <select name="' . $column['name'] . '" class="form-select"';
                if ($column['required']) {
                    $output .= ' required';
                }
                $output .= '>' . "\n";
                $output .= '                <option value="">Choose</option>' . "\n";
                $output .= '            @foreach(' . $column['relation_model'] . '::get() as $item)' . "\n";
                $output .= '                <option value="{{ $item->id }}">{{ $item->' . $column['relation_display'] . ' }}</option>' . "\n";
                $output .= '            @endforeach' . "\n";
                $output .= '        </select>' . "\n";
            }
            $output .= '    </div>' . "\n";
            $output .= '</div>' . "\n";
        }
        $output .= "            </form>";
        $output .= "        </div>";
        $output .= "    </div>";
        $output .= "</div>";

        $output .= "@endsection\n";

        $path = resource_path("views/{$tableName}");
        if ($modules != null) {
            $path = $modules . resource_path("views/{$tableName}");
        }
        if (!File::exists($path)) {
            mkdir($path, 777, true);
        }

        File::put($path, $output);
        // return  view('generated');
    }
}
