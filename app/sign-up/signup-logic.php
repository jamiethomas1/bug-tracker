<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/userController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php-scripts/randomString.php');

if (empty($_POST['username'])) {
    die("Username is required.");
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    die("Invalid email.");
}

if (strlen($_POST['password']) < 8) {
    die("Password must be at least 8 characters.");
}

if (!preg_match("/[a-z]/i", $_POST['password'])) {
    die("Password must contain at least one letter.");
}

if (!preg_match("/[0-9]/", $_POST['password'])) {
    die("Password must contain at least one number.");
}

if ($_POST['password'] !== $_POST['confirm-password']) {
    die("Passwords must match.");
}

$un = htmlspecialchars($_POST['username']);
$em = htmlspecialchars($_POST['email']);
$pw = htmlspecialchars($_POST['password']);
$pw_repeat = htmlspecialchars($_POST['confirm-password']);

$password_hash = password_hash($pw, PASSWORD_DEFAULT);

$userID = getRandomString();

$dbh = new UserController();

if (!empty($_POST) && $pw === $pw_repeat) {
    $dbh->setUser($un, $em, $password_hash, $userID);
    header("Location: signup-success.html");
    exit;
}