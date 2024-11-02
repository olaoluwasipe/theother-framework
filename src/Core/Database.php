<?php
namespace Core;

use Illuminate\Database\Capsule\Manager as Capsule;
use InvalidArgumentException;

class Database {
    public function __construct() {
        $this->initializeEloquent();
    }

    protected function initializeEloquent() {
        $capsule = new Capsule;

        $config = require __DIR__ . '/../../config/database.php';
        
        if (empty(config('database.default'))) {
            throw new InvalidArgumentException('Default database connection not set in configuration.');
        }

        // Add each connection defined in the configuration
        foreach (config('database.connections') as $name => $settings) {
            $capsule->addConnection($settings, $name);
        }

        // Set the global connection to the default one
        $capsule->setAsGlobal();
        
        $capsule->bootEloquent();
    }
}

