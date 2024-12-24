<?php
namespace Core;

use Illuminate\Cache\CacheManager;
use Illuminate\Filesystem\Filesystem;

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

        // Initialize CacheManager with the app container
        $cacheManager = new CacheManager($app);
        $this->cache = $cacheManager->driver();
    }

    public function getCache()
    {
        return $this->cache;
    }
}
