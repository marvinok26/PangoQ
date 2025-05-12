<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// For Laravel 12, try to use handleRequest if it exists
if (method_exists($app, 'handleRequest')) {
    try {
        $app->handleRequest(Request::capture());
    } catch (\Throwable $e) {
        // Fallback to traditional method if handleRequest fails
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle(
            $request = Request::capture()
        );
        $response->send();
        $kernel->terminate($request, $response);
    }
} else {
    // Traditional Laravel approach for earlier versions
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
}