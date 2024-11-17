<?php

require realpath(__DIR__ . '/../../public/index.php');

use Illuminate\Database\Capsule\Manager as Capsule;

$migrationsPath = __DIR__ . '/../../database/migrations';

// Ensure the migrations table exists
if (!Capsule::schema()->hasTable('migrations')) {
    Capsule::schema()->create('migrations', function ($table) {
        $table->id();
        $table->string('migration')->unique();
        $table->integer('batch');
        $table->timestamps();
    });
}

// Get executed migrations
$executedMigrations = Capsule::table('migrations')->pluck('migration')->toArray();

// Determine the new batch number
$currentBatch = Capsule::table('migrations')->max('batch') ?? 0;
$newBatch = $currentBatch + 1;

// Run pending migrations
foreach (glob($migrationsPath . '/*.php') as $migrationFile) {
    $migrationName = basename($migrationFile, '.php'); // Remove `.php` extension

    if (!in_array($migrationName, $executedMigrations)) {
        require_once $migrationFile;
        $currentYear = date('Y');
        $parts = explode('_'.$currentYear, $migrationName);
        $className = $parts[0];

        if (class_exists($className)) {
            $migrationInstance = new $className();

            if (method_exists($migrationInstance, 'up')) {
                $migrationInstance->up();

                // Log migration as executed
                Capsule::table('migrations')->insert([
                    'migration' => $migrationName,
                    'batch' => $newBatch,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                echo "Migrated: $migrationName\n";
            } else {
                echo "Error: 'up' method not found in $migrationName\n";
            }
        } else {
            echo "Error: Class $migrationName does not exist in $migrationFile\n";
        }
    } else {
        echo "Skipped: $migrationName (already executed)\n";
    }
}
