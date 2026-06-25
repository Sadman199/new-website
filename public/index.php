<?php
// ===========================
// Force display all errors
// ===========================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===========================
// Load Laravel kernel
// ===========================
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ===========================
// Maintenance mode check
// ===========================
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// ===========================
// Composer autoload
// ===========================
require __DIR__.'/../vendor/autoload.php';

// ===========================
// Bootstrap Laravel app
// ===========================
$app = require_once __DIR__.'/../bootstrap/app.php';

// ===========================
// Run the application
// ===========================
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
