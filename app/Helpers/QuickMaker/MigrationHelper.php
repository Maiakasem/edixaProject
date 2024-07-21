<?php

namespace App\Helpers\QuickMaker;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MigrationHelper
{
    /* Author : Omar Khaled
     * Documented At : 15-7-2024
     * Last Update At : 15-7-2024
     * Description : Function Goal is Create Migration File For Quick Maker Section
     * Parameters : [
     * @pram string $tableName
     * @pram string $columnStrings
     * ]
     *
     *  */
    public function createMigration(string $tableName,  $columnStrings): array
    {
        // Convert Table Name to snake cases and plural
        $tableName = Str::snake(Str::plural($tableName));
        // Check If Migration Already Exists or not
        if ($this->migrationExists($tableName)) { // if Already Exists return error message
            return [
                'message' => 'Migration already exists for table: ' . $tableName,
                'success' => false
            ];
        }
        // Get location path of Custom Stub for migration
        $stubPath = base_path('stubs/custom/migration.stub');
        // Create Migration File Name with Common naming
        $migrationName = 'create_' . Str::snake($tableName) . '_table';
        $fileName = date('Y_m_d_His') . '_' . $migrationName . '.php';
        // Select Migration File Path
        $migrationPath = database_path('migrations/' . $fileName);
        // Read the stub file
        $filesystem = new Filesystem;
        $stub = $filesystem->get($stubPath);
        // Replace placeholders
        $stub = str_replace('{{tableName}}', $tableName, $stub);
        $stub = str_replace('{{columns}}', implode("\n", $columnStrings), $stub);
        // Save the new migration file
        $filesystem->put($migrationPath, $stub);
        return [
            'message' => 'Migration successfully created',
            'success' => true
        ];
    }
    /* Author : Omar Khaled
     * Documented At : 15-7-2024
     * Last Update At : 15-7-2024
     * Description : Function Goal is Check if migration file is existing or not
     * Parameters : [
     * @pram string $tableName
     * ]
     *
     *  */
    private function migrationExists(string $tableName): bool
    {
        $migrationFiles = File::files(database_path('migrations'));
        foreach ($migrationFiles as $file) {
            if (Str::contains($file->getFilename(), Str::snake($tableName))) {
                return true;
            }
        }
        return false;
    }
}
