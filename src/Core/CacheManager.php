<?php

namespace Core;

use Illuminate\Support\Facades\Cache;

class CacheManager
{
    /**
     * Retrieve cached data or execute a query to populate the cache.
     *
     * @param string $key The cache key.
     * @param \Closure $callback The query to execute if the cache is empty.
     * @param int $ttl Time-to-live in seconds.
     * @return mixed
     */
    public static function remember($key, \Closure $callback, $ttl = 3600)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Forget a cached entry.
     *
     * @param string $key The cache key.
     * @return void
     */
    public static function forget($key)
    {
        Cache::forget($key);
    }

    /**
     * Clear all cache entries for a specific table.
     *
     * @param string $tableName
     * @return void
     */
    public static function clearTableCache($tableName)
    {
        $cacheKeys = Cache::get("cache_keys_{$tableName}", []);
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        Cache::forget("cache_keys_{$tableName}");
    }

    /**
     * Register a cache key for tracking.
     *
     * @param string $tableName
     * @param string $key
     * @return void
     */
    private static function registerCacheKey($tableName, $key)
    {
        $cacheKeys = Cache::get("cache_keys_{$tableName}", []);
        $cacheKeys[] = $key;
        Cache::put("cache_keys_{$tableName}", array_unique($cacheKeys), 86400);
    }
}
