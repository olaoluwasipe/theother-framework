<?php

require realpath(__DIR__ . '/../../public/index.php');

use Illuminate\Database\Capsule\Manager as Capsule;

$lastBatch = Capsule::table('migrations')->max('batch');

if ($lastBatch) {
    // Get the migrations to rollback in the latest batch
    $migrationsToRollback = Capsule::table('migrations')
        ->where('batch', $lastBatch)
        ->orderBy('id', 'desc')
        ->get();

    foreach ($migrationsToRollback as $migration) {
        $migrationFile = __DIR__ . '/../../database/migrations/' . $migration->migration . '.php';

        if (file_exists($migrationFile)) {
            require_once $migrationFile;

            $className = pathinfo($migrationFile, PATHINFO_FILENAME); // Use the migration file name as the class name

            if (class_exists($className)) {
                $migrationInstance = new $className();

                if (method_exists($migrationInstance, 'down')) {
                    $migrationInstance->down(); // Rollback the migration

                    // Remove the migration record from the database
                    Capsule::table('migrations')->where('id', $migration->id)->delete();
                    echo "Rolled back: $migration->migration\n";
                } else {
                    echo "Error: 'down' method not found in $className\n";
                }
            } else {
                echo "Error: Class $className does not exist in $migrationFile\n";
            }
        } else {
            echo "Error: Migration file $migrationFile does not exist\n";
        }
    }
} else {
    echo "No migrations to rollback.\n";
}
