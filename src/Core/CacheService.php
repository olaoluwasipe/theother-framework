<?php
namespace Core;

use Illuminate\Cache\CacheManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Capsule\Manager as DB;

class CacheService
{
    private $cache;

    public function __construct()
    {
        // Initialize Laravel's CacheManager with the default configuration
        $app = new \Illuminate\Container\Container();
        $app->singleton('config', function () {
            // return include realpath('../config/cache.php'); // load your cache config
            return config('cache'); // load your cache config
        });

        $app->singleton('files', function () {
            return new Filesystem();
        });

        // Register Database Manager
        $app->singleton('db', function () {
            $capsule = new DB();
            // $capsule->addConnection(require __DIR__ . '/../config/database.php'); // Ensure this file returns the DB config array
            $capsule->addConnection(config('database.connections.default')); // Ensure this file returns the DB config array
            $capsule->addConnection(config('database.connections.mysql2'), 'mysql2');
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
            return $capsule->getDatabaseManager();
        });

        // Initialize CacheManager with the app container
        $cacheManager = new CacheManager($app);
        $this->cache = $cacheManager->driver();
    }

    public function getCache()
    {
        return $this->cache;
    }
}
