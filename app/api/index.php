<?php

declare(strict_types=1);

ini_set("html_errors", 0);

// Look at using something like Composer for autoloading
spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-Type: application/json; charset=UTF-8");

// Look at using something like Fastroute for routing
$parts = explode("/", $_SERVER['REQUEST_URI']);

$dbh = new Database;

$id = $parts[3] ?? null;

switch ($parts[2]) {
    case "users":
        $gateway = new UserGateway($dbh);
        $controller = new UserController($gateway);
        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;
    case "organisations":
        $gateway = new OrgGateway($dbh);
        $controller = new OrgController($gateway);
        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;
    case "projects":
        $gateway = new ProjGateway($dbh);
        $controller = new ProjController($gateway);
        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;
    default:
        http_response_code(404);
        exit;
}

ini_set("html_errors", 1);
