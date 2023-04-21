<?php

declare(strict_types=1);

ini_set("html_errors", 0);

require "/vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

if ($parts[2] == "auth") {
    $id = $parts[4] ?? null;
} else {
    $id = $parts[3] ?? null;
}

$key = getenv("JWT_SIGNATURE_KEY");
if (array_key_exists("HTTP_AUTHORIZATION", $_SERVER)) {
    $jwt = explode(" ", $_SERVER["HTTP_AUTHORIZATION"])[1];
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    print_r($decoded);
}

switch ($parts[2]) {
    case "auth":
        switch ($parts[3]) {
            case "login":
                $gateway = new LoginGateway($dbh);
                $controller = new LoginController($gateway);
                $controller->processRequest($_SERVER["REQUEST_METHOD"]);
                break;
            default:
                http_response_code(404);
                exit;
        }
        break;
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
    case "tickets":
        $gateway = new TicketGateway($dbh);
        $controller = new TicketController($gateway);
        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;
    case "responses":
        $gateway = new ResponseGateway($dbh);
        $controller = new ResponseController($gateway);
        $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;
    default:
        http_response_code(404);
        exit;
}

ini_set("html_errors", 1);
