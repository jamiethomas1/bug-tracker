<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/userController.class.php';

$un = htmlspecialchars($_POST['username']);
$pw = htmlspecialchars($_POST['password']);
$pw_repeat = htmlspecialchars($_POST['confirm-password']);

$dbh = new UserController();

if (!empty($_POST) && $pw === $pw_repeat) {
    if ($dbh->checkUnique($un)) {
        $dbh->setUser($un, $pw);
    } else {
        echo "Duplicate user.";
    }
}