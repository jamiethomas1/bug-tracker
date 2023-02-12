<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/userController.class.php';

// This file is called by the js/validation.js file to check if the entered email already exists in the DB

$dbh = new UserController();
$user = $dbh->getUserByEmail(htmlspecialchars($_GET["email"]));
$is_available = $user ? false : true;

header("Content-Type: application/json");
echo json_encode(["available" => $is_available]);