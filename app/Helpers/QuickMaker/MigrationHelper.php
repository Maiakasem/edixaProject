<?php

namespace App\Helpers\Dashboard;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MigrationHelper
{
    /**
     * Create a new class instance.
     */
    public function createMigration($tableName, $columnStrings) {
        $tableName = Str::snake(Str::plural($tableName));
        if ($this->migrationExists($tableName)) {
            return [
                'message' => 'Migration already exists for table: ' . $tableName,
                'success' => false
            ];
        }
        $stubPath = base_path('stubs/custom/migration.stub');
        $migrationName = 'create_' . Str::snake($tableName) . '_table';
        $fileName = date('Y_m_d_His') . '_' . $migrationName . '.php';
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
    private function migrationExists($tableName)
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
