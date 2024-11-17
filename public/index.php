<?php
require __DIR__.'/../vendor/autoload.php';

use Core\Database;
use Core\Logger;
use Core\Router;
use Dotenv\Dotenv;

// Load environment
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Initialize the database connection
new Database();

// Load Eloquent
require __DIR__.'/../config/database.php';

// Skip routing when running from CLI
if (php_sapi_name() === 'cli') {
    return;
}

// Initialize router
$router = new Router();
$logger = new Logger();

// Set error handlers
set_error_handler(function($errno, $errstr, $errfile, $errline) use ($logger) {
    $logger->error("[$errno] $errstr in $errfile on line $errline");
});

set_exception_handler(function($exception) use ($logger) {
    $logger->error($exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine());
});

// Log fatal errors
register_shutdown_function(function() use ($logger) {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $logger->error("[FATAL] {$error['message']} in {$error['file']} on line {$error['line']}");
    }
});

require __DIR__.'/../routes/web.php';

// Dispatch request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
