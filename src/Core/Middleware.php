<?php
namespace Core;

class Middleware {
    public static function handle($middleware, $next) {
        if (class_exists($middleware)) {
            (new $middleware)->handle();
        }
        return $next();
    }
}
