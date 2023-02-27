<?php

declare(strict_types=1);

ini_set("html_errors", 0);

// Look at using something like Composer for autoloading
spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_exception_handler("ErrorHandler::handleException");

header("Content-Type: application/json; charset=UTF-8");

// Look at using something like Fastroute for routing
$parts = explode("/", $_SERVER['REQUEST_URI']);

if ($parts[2] != "users") {
    http_response_code(404);
    exit;
}

$id = $parts[3] ?? null;

$dbh = new Database;

$gateway = new ProductGateway($dbh);

$controller = new ProductController($gateway);
$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

ini_set("html_errors", 1);