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

$authenticated = false;
$key = getenv("JWT_SIGNATURE_KEY");
if (array_key_exists("HTTP_AUTHORIZATION", $_SERVER)) {
    $jwt = explode(" ", $_SERVER["HTTP_AUTHORIZATION"])[1];
    try {
        $decoded = (array) JWT::decode($jwt, new Key($key, 'HS256')); // Nothing after this line will run if an exception is thrown
        // If expiry time is in the future
        if ($decoded['exp'] > time()) {
            $authenticated = true;
        } else {
            // Access token expired: generate new one
        }
    } catch (LogicException $e) {
        $authenticated = false;
    } catch (UnexpectedValueException $e) {
        $authenticated = false;
    }
}

switch ($parts[2]) {
    case "auth":
        switch ($parts[3]) {
            case "login":
                $gateway = new LoginGateway($dbh);
                $controller = new LoginController($gateway);
                $controller->processRequest($_SERVER["REQUEST_METHOD"]);
                break;
            case "register":
                $gateway = new RegisterGateway($dbh);
                $controller = new RegisterController($gateway);
                $controller->processRequest($_SERVER["REQUEST_METHOD"]);
                break;
            default:
                http_response_code(404);
                exit;
        }
        break;
    case "users":
        if ($authenticated) {
            $gateway = new UserGateway($dbh);
            $controller = new UserController($gateway);
            $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        } else {
            http_response_code(401);
            exit;
        }
        break;
    case "organisations":
        if ($authenticated) {
            $gateway = new OrgGateway($dbh);
            $controller = new OrgController($gateway);
            $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        } else {
            http_response_code(401);
            exit;
        }
        break;
    case "projects":
        if ($authenticated) {
            $gateway = new ProjGateway($dbh);
            $controller = new ProjController($gateway);
            $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        } else {
            http_response_code(401);
            exit;
        }
        break;
    case "tickets":
        if ($authenticated) {
            $gateway = new TicketGateway($dbh);
            $controller = new TicketController($gateway);
            $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        } else {
            http_response_code(401);
            exit;
        }
        break;
    case "responses":
        if ($authenticated) {
            $gateway = new ResponseGateway($dbh);
            $controller = new ResponseController($gateway);
            $controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        } else {
            http_response_code(401);
            exit;
        }
        break;
    default:
        http_response_code(404);
        exit;
}

ini_set("html_errors", 1);
