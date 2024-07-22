<?php

namespace App\Helpers\QuickMaker;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class RouteHelper
{

    public function routeCreate($uri, $modules = null, $prefix = null, $middleware = null)
    {
        // $newRoute = "\nRoute::resource('/{$uri}', {$controller}::class);\n";
        // // Path to the routes file
        // $routesFilePath = base_path('routes/web.php');
        // // Check if file is writable
        // if (File::isWritable($routesFilePath)) {
        //     // Append the new route to the file
        //     File::append($routesFilePath, $newRoute);
        //     return response()->json(['message' => 'Route added successfully'], 200);
        // } else {
        //     return response()->json(['error' => 'Routes file is not writable'], 500);
        // }

        $controller = Str::studly(Str::singular($uri));
        $newRoute = "\nRoute::resource('/{$uri}', {$controller}Controller::class);\n";
        $routesFilePath = base_path('routes/web.php');
        if ($modules != null) {
            $routesFilePath =   base_path("modules/{$modules}/routes/web.php");
        }
        if (File::isWritable($routesFilePath)) {
            $routesFileContent = File::get($routesFilePath);
            if ($prefix) {
                $prefixPosition = strpos($routesFileContent, "Route::prefix('{$prefix}')");
                if ($prefixPosition === false) {
                    // Create the new route group if not found
                    $newGroup = "\nRoute::";
                    if ($prefix != null) {
                        $newGroup .= "prefix('{$prefix}')->name('{$prefix}.')->";
                    }
                    if ($middleware != null) {
                        $newGroup .= "middleware('{$middleware}')->";
                    }
                    $newGroup .= "group(function () {\n";
                    $newGroup .=    $newRoute;
                    $newGroup .= "});\n";
                    // Append the new group to the end of the routes file
                    $updatedRoutesFileContent = $routesFileContent . $newGroup;
                } else {
                    // Find the position of the group closure end
                    $groupClosureEndPosition = strpos($routesFileContent, "});", $prefixPosition);
                    if ($groupClosureEndPosition === false) {
                        return response()->json(['error' => 'Route group closure not found'], 404);
                    }
                    // Insert the new route just before the end of the group closure
                    $updatedRoutesFileContent = substr_replace(
                        $routesFileContent,
                        "\n" . $newRoute,
                        $groupClosureEndPosition,
                        0
                    );
                }
            } else {
                // Append the new route to the end of the routes file
                $updatedRoutesFileContent = $routesFileContent . "\n" . $newRoute;
            }
            // Write the updated content back to the routes file
            File::put($routesFilePath, $updatedRoutesFileContent);

            return response()->json(['message' => 'Route added successfully'], 200);
        } else {
            return response()->json(['error' => 'Routes file is not writable'], 500);
        }
    }
}
