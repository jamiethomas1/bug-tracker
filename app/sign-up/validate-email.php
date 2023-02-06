<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/userController.class.php';

$dbh = new UserController();
$user = $dbh->getUserByEmail(htmlspecialchars($_GET["email"]));
$is_available = $user ? false : true;

header("Content-Type: application/json");
echo json_encode(["available" => $is_available]);